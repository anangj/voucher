<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = ['rm_no', 'registration_no', 'name', 'birthday', 'email', 'phone'];

    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }

    public function familyMember()
    {
        return $this->hasOne(FamilyMember::class);
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
