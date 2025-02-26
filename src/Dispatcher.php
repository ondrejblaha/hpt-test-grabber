<?php

declare(strict_types=1);

namespace HPT;

class Dispatcher
{
    private Grabber $grabber;
    private Output $output;

    private string $inputFile = './input.txt';

    /**
     * @param Grabber $grabber
     * @param Output $output
     */
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

        $productCodes = $this->loadInputFile($this->inputFile);

        if (count($productCodes) > 0) {
            foreach ($productCodes as $productCode) {

                $product = $this->grabber->findProduct($productCode);

                if ($product !== null) {
                    $this->output->addProduct($product);
                } else {
                    $this->output->addProduct(new Product($productCode));
                }
            }
        }

        return $this->output->getJson();
    }

    /**
     * @param string $filePath
     * @return array
     */
    private function loadInputFile(string $filePath): array
    {

        $productCodes = array();

        if (file_exists($filePath)) {
            if ($f = fopen($filePath, 'r')) {

                while (($code = fgets($f)) !== false) {
                    $productCodes[] = trim($code);
                }
                fclose($f);
            }
        }

        return $productCodes;
    }
}
