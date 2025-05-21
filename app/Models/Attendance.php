<?php

/**
 * @OA\Schema(
 *   schema="Attendance",
 *   type="object",
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="employee_id", type="integer", example=1),
 *   @OA\Property(property="date", type="string", format="date", example="2025-05-10"),
 *   @OA\Property(property="check_in", type="string", format="time", example="09:00:00"),
 *   @OA\Property(property="check_out", type="string", format="time", example="18:00:00"),
 *   @OA\Property(property="status", type="string", example="Present"),
 *   @OA\Property(property="company_id", type="integer", example=1),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'company_id',
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
