<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VoucherHeader extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';



    protected $fillable = [
        'paket_voucher_id',
        'patient_id',
        'voucher_header_no',
        'purchase_date',
        'expiry_date',
        'current_uses',
        'last_reminder_sent_at',
        'status',
        'isExtend',
        'reason'
    ];

    public function voucherDetail()
    {
        return $this->hasMany(VoucherDetail::class);
    }

    public function paketVoucher()
    {
        return $this->belongsTo(PackageVoucher::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function history()
    {
        return $this->hasMany(VoucherHistory::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    // Automatically assign a UUID when creating the model
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }
}
