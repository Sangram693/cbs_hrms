<?php

namespace App\Observers;

use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceObserver
{
    /**
     * Handle the Attendance "saving" event.
     */
    public function saving(Attendance $attendance)
    {
        // Only update status automatically if not explicitly set
        if (!$attendance->isDirty('status')) {
            // Default to Absent if no check-in/check-out
            if (!$attendance->check_in || !$attendance->check_out) {
                $attendance->status = 'Absent';
                return;
            }

            // Parse check-in time
            $checkIn = Carbon::createFromFormat('H:i:s', $attendance->check_in);
            $checkOut = Carbon::createFromFormat('H:i:s', $attendance->check_out);

            // Working hours calculation
            $workingHours = $checkOut->diffInHours($checkIn);

            // If worked less than 4 hours, mark as Absent
            // If worked between 4-6 hours, mark as Half Day (keeping as Present for now since Half Day not in enum)
            // If worked more than 6 hours, mark as Present
            if ($workingHours < 4) {
                $attendance->status = 'Absent';
            } else {
                $attendance->status = 'Present';
            }
        }
    }
}
