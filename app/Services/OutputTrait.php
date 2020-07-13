<?php

namespace App\Services;

use DOMDocument;

trait OutputTrait
{
    /**
     * The XML output.
     *
     * @param  string       $status
     * @param  string|null  $message
     *
     * @return string
     */
    public function output(string $status, string $message = null): string
    {
        $domDocument = new DOMDocument('1.0');
        $resultElement = $domDocument->createElement('result');

        $statusAttribute = $domDocument->createAttribute('status');
        $statusAttribute->value = $status;
        $resultElement->appendChild($statusAttribute);

        if ($message) {
            $msgAttribute = $domDocument->createAttribute('msg');
            $msgAttribute->value = $message;
            $resultElement->appendChild($msgAttribute);
        }

        $domDocument->appendChild($resultElement);

        return $domDocument->saveXML();
    }
}
