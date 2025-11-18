<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'contact_email',
        'contact_phone',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
