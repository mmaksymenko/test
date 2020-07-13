<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * @inheritDoc
     */
    public const UPDATED_AT = null;

    /**
     * @inheritDoc
     */
    protected $table = 'user_transactions';
}
