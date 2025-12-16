<?php

namespace App\Repository;

use App\Models\Seminar;
use App\Models\User;

class SeminarRepository
{
    public function create(array $data, User $creator)
    {
        $seminar = new Seminar();
        $seminar->fill($data);
        $seminar->creator()->associate($creator);
        $seminar->save();

        return $seminar;
    }

    public function update(Seminar $seminar, array $data)
    {
        $seminar->fill($data);
        return $seminar->save();
    }
}
