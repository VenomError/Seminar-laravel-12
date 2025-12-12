<?php
namespace App\Models;

use App\Models\User;
use App\Models\Payment;
use App\Models\Seminar;
use App\Models\Attendence;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'user_id',
        'seminar_id',
        'ticket_code',
        'status'
    ];

    // Relasi: User pemilik tiket
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Seminar terkait
    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }

    // Relasi: Pembayaran (One to One)
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Relasi: Absensi (One to One)
    public function attendance()
    {
        return $this->hasOne(Attendence::class);
    }
}