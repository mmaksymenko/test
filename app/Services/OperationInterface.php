<?php

namespace App\Services;

interface OperationInterface
{
    /**
     * @return string
     */
    public function process(): string;
}
