<?php

declare(strict_types=1);

namespace HPT;

class Dispatcher
{
    private Grabber $grabber;
    private Output $output;
    private $status = false;
    private $statusText = '';

    public function __construct(Grabber $grabber, Output $output)
    {
        $this->grabber = $grabber;
        $this->output = $output;
    }

    /**
     * @return string JSON
     */
    public function run(): string
    {

        $configFile = './input.txt';
        // nacteni configu
        if ($f = fopen($configFile,'r')) {
            $fileContent = '';

            while (!feof($f)) {
                $fileContent .= fread($f, 8192);
            }
            fclose($f);
        } else {
            $this->statusText = 'Undefined config file';
            $this->status = false;
        }

        $productList = explode("\n",$fileContent);

        if(count($productList)>0) {
            foreach($productList as $productCode) {

                $productCode = trim($productCode);
                if(strlen($productCode)>0) {

                    $urls = $this->grabber->findProducts($productCode);

                    if(count($urls)>0) {
                        foreach($urls as $url) {
                            $this->grabber->getProduct($url,$productCode);
                            $this->output->addProductData($productCode, $this->grabber->getPrice($productCode), $this->grabber->getRatting($productCode), $this->grabber->getName($productCode));
                        }
                    } else {
                        $this->output->addProductData($productCode);
                    }
                }
            }
        } else {
            $this->statusText = 'No products id';
            $this->status = false;
        }

        
        return $this->output->getJson();
    }
}
