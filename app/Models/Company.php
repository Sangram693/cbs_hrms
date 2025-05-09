<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
     public $incrementing = false;

    // Set the key type to string because UUIDs are strings
    protected $keyType = 'string';
    
     protected $fillable = [
        'id', 'name', 'address', 'phone',
    ];

    // A company can have many users (employees)
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public static function getAllCompaniesForSuperAdmin(User $user)
    {
        if ($user->isSuperAdmin()) {
            return self::all();  // Super admin can access all companies
        }

        return $user->company()->get(); // Admin can only access their own company
    }
}
