<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Disable auto-incrementing IDs (because we're using UUIDs)
    public $incrementing = false;

    // Set the key type to string because UUIDs are strings
    protected $keyType = 'string';

    // Fillable fields for mass assignment
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'role',
        'company_id',
        'active', // Add this line
    ];

     protected $hidden = [
        'password',
        'remember_token',
    ];

    // Automatically hash password when set
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'id');
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }
    
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

     public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if the user is HR for any department.
     */
    public function isHr(): bool
    {
        $employee = $this->employee;
        if (!$employee) return false;
        return \App\Models\Department::where('hr_id', $employee->id)->exists();
    }
}
