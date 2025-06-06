<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBill extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'employee_id',
        'bill_type',
        'amount',
        'description',
        'file_path',
        'status',
        'rejection_reason',
        'bill_date',
        'approved_by',
        'company_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'bill_date' => 'date',
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

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
