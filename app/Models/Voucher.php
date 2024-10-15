<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = ['voucher_no', 'qr_code', 'paket_voucher_id', 'patient_id', 'issued_to_family_member', 'purchase_date', 'generate_date', 'expiry_date', 'status', 'max_uses', 'current_uses', 'last_reminder_sent_at'];

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
