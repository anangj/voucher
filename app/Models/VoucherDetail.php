<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VoucherDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_header_id',
        'voucher_no',
        'qr_code',
        'is_used',
        'using_date',
        'issued_to_family_member'
    ];

    public function voucherHeader()
    {
        return $this->belongsTo(VoucherHeader::class);
    }

    public function voucherHistories()
    {
        return $this->hasMany(VoucherHistory::class);
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
