<?php

declare(strict_types=1);

namespace HPT;

class Dispatcher
{
    private Grabber $grabber;
    private Output $output;

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
        // code here

        return $this->output->getJson();
    }
}
