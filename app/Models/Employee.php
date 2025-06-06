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
        'designation_id',
        'hire_date',
        'salary',
        'user_role',
        'fingerprint_id',
        'photo',
        'status',
        'company_id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }    public function designation()
    {
        return $this->belongsTo(Designation::class);
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
}
