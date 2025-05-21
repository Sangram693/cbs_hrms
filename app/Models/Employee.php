<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'emp_id',
        'name',
        'email',
        'phone',
        'department_id',
        'position_id',
        'hire_date',
        'salary',
        'user_role',
        'fingerprint_id',
        'photo',
        'status',
        'company_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }

    public function trainings()
    {
        return $this->hasMany(Training::class);
    }

    public function logins()
    {
        return $this->hasMany(Login::class);
    }
}
