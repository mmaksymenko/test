<?php

namespace App\Services;

use Illuminate\Support\Str;

final class OperationService
{
    /**
     * @var OperationInterface
     */
    private $operation;

    /**
     * OperationService constructor.
     *
     * @param string $type
     * @param string $userId
     * @param string $transactionId
     * @param int $amount
     */
    public function __construct(string $type, string $userId, string $transactionId, int $amount)
    {
        $class = 'App\Services\Operations\\' . Str::ucfirst($type) . 'Operation';
        $this->setOperation(new $class($userId, $transactionId, $amount));
    }

    /**
     * @param  OperationInterface  $operation
     *
     * @return void
     */
    private function setOperation(OperationInterface $operation): void
    {
        $this->operation = $operation;
    }

    /**
     * @return string
     */
    public function process(): string
    {
        return $this->operation->process();
    }
}
