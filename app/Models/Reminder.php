<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'voucher_id', 'reminder_type', 'scheduled_at', 'sent_at'];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
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
