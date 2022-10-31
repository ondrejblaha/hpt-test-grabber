<?php

declare(strict_types=1);

namespace HPT;

class GrabberClass implements Grabber {

    public $productsData = array();
    private $strictMode = true; // kod se musi presne rovnat - pro vice vyhledanych produktu

    public function __construct() {

    }

    public function getPrice(string $productId): float {
        return $this->productsData[$productId]['price'] ?? (float)0;
    }
    public function getRatting(string $productId): float {

        return $this->productsData[$productId]['ratting'] ?? (float)0;
    }
    public function getName(string $productId): string {

        return $this->productsData[$productId]['name'] ?? (string)'';
    }

    public function findProducts(string $productCode): array {

        $searchUrl = 'https://www.czc.cz/'.trim($productCode).'/hledat';
        $urls = array();

        # kontrola na existenci url
        $file_headers = @get_headers($searchUrl);
        if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
            return $urls;
        }

        $content = file_get_contents($searchUrl);

        preg_match_all("'<a class=\"tile-link\"(.*?)tabindex=\"-1\"></a>'si", $content, $matches);        
        if(count($matches) > 0) {
            foreach($matches[1] as $productUrl) {
                $urls[] = trim(str_replace(array('href=','"'),'',$productUrl));
            } 
        }

        return $urls;
    }

    public function getProduct(string $productUrl,string $productCode): void {
    
        $link = 'https://www.czc.cz'.$productUrl;

        # kontrola na existenci url
        $file_headers = @get_headers($link);
        if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
            return;
        }

        $content = file_get_contents($link);

        # code
        $code = '';
        preg_match("'<span class=\"pd-next-in-category__item-value\">(.*?)</span>'si", $content, $matches);
        if(isset($matches[1])) {
            $code = str_replace('&nbsp;','', htmlentities($matches[1]));
        }

        # kontrola na presny kod -> pro nacteni podobnych kodu je potreba zmenit -> private $strictMode = false;
        if($this->strictMode && $code !== trim($productCode)) {

            $this->productsData[$code] = array('price' => (float)0, 'ratting' => (float)0, 'name' => '');
            return;
        }

        # price box -> vice cen se stejnou class
        $price = 0;
        preg_match("'<div class=\"pd-price-wrapper\">(.*?)</div>'si", $content, $matchesBox);
        if(isset($matchesBox[1])) {

            # price
            preg_match("'<span class=\"price-vatin\">(.*?)Kč</span>'si", $matchesBox[1], $matches);
            if(isset($matches[1])) {
                $price = str_replace('&nbsp;','', htmlentities($matches[1]));
            }
        }

        # ratting
        $ratting = 0;
        preg_match("'<span class=\"rating__label\">(.*?) %</span>'si", $content, $matches);
        if(isset($matches[1])) {
            $ratting = str_replace('&nbsp;','', htmlentities($matches[1]));
        }
        


        # name
        $name = '';
        preg_match("'<h1 title(.*?)</h1>'si", $content, $matches);
        if(isset($matches[1])) {
            $list = explode('>', $matches[1]);
            $name = trim(str_replace('&nbsp;','', htmlentities(end($list))));
        }
        
        $this->productsData[$code] = array('price' => (float)$price, 'ratting' => (float)$ratting, 'name' => (string)$name);
    }
}