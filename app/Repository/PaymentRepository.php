<?php

namespace App\Repository;

use App\Models\Payment;
use App\Models\Registration;

class PaymentRepository
{
    public function create(array $data, Registration $registration)
    {
        $payment = new Payment();
        $payment->fill($data);
        $payment->registration()->associate($registration);

        $payment->save();

        return $payment;
    }
}
