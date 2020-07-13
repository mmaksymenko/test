<?php

namespace App\Models;

use App\Models\User\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Uuid;

class User extends Model
{
    /**
     * @inheritDoc
     */
    protected $fillable = [
        'name', 'balance',
    ];

    /**
     * @inheritDoc
     */
    protected $keyType = 'string';

    /**
     * @inheritDoc
     */
    public $incrementing = false;

    /**
     * @inheritDoc
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), str_replace('-', '', Uuid::uuid4()));
        });
    }

    /**
     * Get the transactions for the user.
     *
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
