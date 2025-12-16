<?php

namespace App\Repository;

use App\Models\Attendence;
use App\Models\Registration;
use App\Models\User;
use Carbon\Carbon;
use DateTime;

class AttendenceRepository
{
    public function create(Carbon|DateTime $scanned_at, User $scanned_by, Registration $registration)
    {
        // ❌ Cegah absen ganda
        if ($registration->attendence) {
            throw new \Exception("Peserta sudah melakukan absensi");
        }

        // ❌ Validasi user
        if ($scanned_by->id !== $registration->user_id) {
            throw new \Exception("Akun pendaftar tidak sesuai");
        }

        $attendence = new Attendence();
        $attendence->scanned_at = $scanned_at;
        $attendence->scanner()->associate($scanned_by);
        $attendence->registration()->associate($registration);
        $attendence->save();

        return $attendence;
    }
}
