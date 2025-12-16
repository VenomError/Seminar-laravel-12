<?php

namespace App\Repository;

use App\Enum\RegistrationStatus;
use App\Models\Registration;
use App\Models\Seminar;
use App\Models\User;

class RegistrationRepository
{
    public function create(User $user, Seminar $seminar, RegistrationStatus $status): Registration
    {
        $registration = new Registration();
        $registration->user()->associate($user);
        $registration->seminar()->associate($seminar);
        $registration->status = $status;

        $registration->save();

        return $registration;
    }
}
