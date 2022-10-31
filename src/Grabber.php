<?php

declare(strict_types=1);

namespace HPT;

interface GrabberInterface
{
    public function getPrice(string $productId): float;
}

class Grabber implements GrabberInterface {

    public $productsData = array();
    private $strictMode = true; // kod se musi presne rovnat - pro vice vyhledanych produktu

    public function __construct() {

    }

    public function getPrice(string $productId): float {

        return isset($this->productsData[$productId]['price'])?$this->productsData[$productId]['price']:0;
    }

    public function findProducts(string $productCode) {

        $searchUrl = 'https://www.czc.cz/'.trim($productCode).'/hledat';

        $content = file_get_contents($searchUrl);

        preg_match_all("'<a class=\"tile-link\"(.*?)tabindex=\"-1\"></a>'si", $content, $matches);

        $urls = array();
        if(count($matches) > 0) {
            //print_r($matches);
            foreach($matches[1] as $productUrl) {
                $urls[] = trim(str_replace(array('href=','"'),'',$productUrl));
            } 
        }

        return $urls;
    }

    public function getProduct(string $productUrl,string $productCode) {
     
        $link = 'https://www.czc.cz'.$productUrl;
        $content = file_get_contents($link);


        # code
        $code = '';
        preg_match("'<span class=\"pd-next-in-category__item-value\">(.*?)</span>'si", $content, $matches);
        if(isset($matches[1])) {
            $code = str_replace('&nbsp;','', htmlentities($matches[1]));
        }

        # kontrola na presny kod -> pro nacteni podobnych kodu je potreba zmenit -> private $strictMode = false;
        if($this->strictMode && $code != trim($productCode)) {

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

        // <h1 title="Název produktu: Fractal Design Node 804" aria-label="Fractal Design Node 804">Fractal Design Node 804</h1>
        $this->productsData[$code] = array('price' => (float)$price);
    }
}