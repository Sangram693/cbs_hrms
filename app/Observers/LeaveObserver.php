<?php

namespace App\Observers;

use App\Models\Leave;
use App\Models\Attendance;
use Carbon\Carbon;

class LeaveObserver
{
    /**
     * Handle the Leave "updated" event.
     */
    public function updated(Leave $leave)
    {
        // Check if status was changed to Approved
        if ($leave->isDirty('status') && $leave->status === 'Approved') {
            $start = Carbon::parse($leave->start_date);
            $end = Carbon::parse($leave->end_date);

            // Create attendance records for each day in the leave period
            for ($date = $start; $date->lte($end); $date->addDay()) {
                // Skip weekends if needed
                if ($date->isSunday()) {
                    continue;
                }

                if ($date->isSaturday()) {
                        $weekOfMonth = $date->weekOfMonth;
                        if ($weekOfMonth === 2 || $weekOfMonth === 4) {
                            continue; // Skip 2nd and 4th Saturday
                            }
                        }

                // Check if attendance record already exists
                $exists = Attendance::where([
                    'employee_id' => $leave->employee_id,
                    'company_id' => $leave->company_id,
                    'date' => $date->format('Y-m-d')
                ])->exists();

                // Create attendance record if it doesn't exist
                if (!$exists) {
                    Attendance::create([
                        'employee_id' => $leave->employee_id,
                        'company_id' => $leave->company_id,
                        'date' => $date->format('Y-m-d'),
                        'status' => 'Leave',
                        'check_in' => null,
                        'check_out' => null
                    ]);
                }
            }
        }
    }
}
