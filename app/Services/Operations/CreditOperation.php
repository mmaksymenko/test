<?php

namespace App\Services\Operations;

use App\Models\User;
use App\Services\OperationInterface;
use App\Services\OutputTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

final class CreditOperation implements OperationInterface
{
    use OutputTrait;

    /**
     * The user id.
     *
     * @var string
     */
    protected $userId;

    /**
     * The user transaction id.
     *
     * @var string
     */
    protected $transactionId;

    /**
     * Transaction amount.
     *
     * @var int
     */
    protected $amount;

    /**
     * CreditOperation constructor.
     *
     * @param  string  $userId
     * @param  string  $transactionId
     * @param  int     $amount
     */
    public function __construct(string $userId, string $transactionId, int $amount)
    {
        $this->userId = $userId;
        $this->transactionId = $transactionId;
        $this->amount = $amount;
    }

    public function process(): string
    {
        return DB::transaction(function () {
            $user = User::lockForUpdate()->with(['transactions' => function (HasMany $query) {
                $query->where('transaction_id', $this->transactionId);
            }])->find($this->userId);
            if ($user->transactions->count() === 0) {
                $transaction = new User\Transaction();
                $transaction->transaction_id = $this->transactionId;
                $user->transactions()->save($transaction);

                $user->balance += $this->amount;
                $user->save();
            }

            return $this->output('OK');
        });
    }
}
