<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'patient_id', 'voucher_header_id', 'amount', 'purchase_date', 'payment_method', 'status', 'no_card'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function voucherHeader()
    {
        return $this->belongsTo(VoucherHeader::class);
    }

    // This tells Laravel the primary key is not an integer
    public $incrementing = false;

    // The primary key is of type UUID
    protected $keyType = 'string';

    // Automatically assign a UUID when creating the model
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }
}
