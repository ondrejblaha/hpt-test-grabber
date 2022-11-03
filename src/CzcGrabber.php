<?php

declare(strict_types=1);

namespace HPT;

use DOMDocument;
use DOMXPath;

class CzcGrabber implements Grabber
{

    public function __construct()
    {

    }

    /**
     * @param string $productUrl
     * @return string
     */
    private function getProductDetailUrl(string $productUrl): string
    {
        return 'https://www.czc.cz' . $productUrl;
    }

    /**
     * @param string $productCode
     * @return string
     */
    private function getProductSearchUrl(string $productCode): string
    {
        return 'https://www.czc.cz/' . trim($productCode) . '/hledat';
    }

    /**
     * @param string $url
     * @return DOMXPath|null
     */
    private function getPageDOMXpath(string $url): ?DOMXPath
    {
        # CURL call 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $html = curl_exec($ch);
        $curl_errno = curl_errno($ch);
        curl_close($ch);

        if ($curl_errno === 0) {

            # nacteni stranky - potlaceni chyb pri $dom->loadHTML
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML(str_replace("&nbsp;", "", $html));
            libxml_use_internal_errors(false);

            # vyhodnotim vystup vyhledavani
            $xpath = new DOMXPath($dom);
        }
        return $xpath ?? null;
    }


    /**
     * @param string $productCode
     * @return Product|null
     */
    public function findProduct(string $productCode): ?Product
    {

        # build search URL
        $url = $this->getProductSearchUrl($productCode);

        $xpath = $this->getPageDOMXpath($url);

        if ($xpath !== null) {
            $products = $xpath->query('//a[@class="tile-link"]');
            if ($products) {
                foreach ($products as $productItem) {
                    $url = (string)$productItem->getAttribute("href");
                    $product = $this->getProduct($url, $productCode);
                }
            }
        }

        return $product ?? null;
    }

    /**
     * @param string $productUrl
     * @param string $productCode
     * @return Product|null
     */
    private function getProduct(string $productUrl, string $productCode): ?Product
    {

        $productUrl = $this->getProductDetailUrl($productUrl);

        $xpath = $this->getPageDOMXpath($productUrl);

        if ($xpath !== null) {

            $code = '';
            $codeNode = $xpath->query('//span[@class="pd-next-in-category__item-value"]');
            if ($codeNode->length > 0) {
                $code = trim($codeNode->item(0)->textContent);
            }

            # kontrola ze jsem nasel spravny product
            if ($code === $productCode) {

                $priceNode = $xpath->query('//span[@class="price-vatin"]');
                if ($priceNode->length > 0) {
                    $priceText = $priceNode->item(0)->textContent;
                    # odstraneni mezer a meny
                    $price = floatval(str_replace(array('&nbsp;', 'Kč', ' ', '.', ','), '', htmlentities($priceText)));
                }

                $rattingNode = $xpath->query('//span[@class="rating__label"]');
                if ($rattingNode->length > 0) {
                    $rattingText = $rattingNode->item(0)->textContent;
                    # odstraneni mezer a procenta
                    $ratting = floatval(str_replace(array('&nbsp;', '%', ' '), '', htmlentities($rattingText)));
                }

                $nameNode = $xpath->query('//div[@class="pd-wrap"]/h1');
                if ($nameNode->length > 0) {
                    $name = trim($nameNode->item(0)->textContent);
                }

                $product = new Product($productCode, $price ?? null, $name ?? null, $ratting ?? null);
            }
        }
        return $product ?? null;
    }
}