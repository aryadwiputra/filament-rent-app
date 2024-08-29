<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'store_id',
        'trx_id',
        'name',
        'phone_number',
        'address',
        'total_amount',
        'is_paid',
        'proof',
        'duration',
        'started_at',
        'ended_at',
        'delivery_type',
    ];

    protected $casts = [
        'total_amount' => MoneyCast::class,
        'started_at' => 'date',
        'ended_at' => 'date',
    ];

    public function generateUniqueTrxId()
    {
        $prefix = 'SEWA-';
        do {
            $trx_id = $prefix . mt_rand(1000, 9999);
        } while (Transaction::where('trx_id', $trx_id)->exists());
        return $trx_id;
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
