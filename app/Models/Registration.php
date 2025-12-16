<?php
namespace App\Models;

use App\Enum\RegistrationStatus;
use App\Models\Attendence;
use App\Models\Payment;
use App\Models\Seminar;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Registration extends Model
{
    protected $fillable = [
        'user_id',
        'seminar_id',
        'ticket_code',
        'status'
    ];

    protected $casts = [
        'status' => RegistrationStatus::class,
    ];

    /**
     * Auto logic saat model dibuat
     */
    protected static function booted()
    {
        static::creating(function (Registration $registration) {
            // ✅ Generate ticket code otomatis
            if (empty($registration->ticket_code)) {
                $registration->ticket_code = self::generateTicketCode(
                    $registration->seminar_id,
                    $registration->user_id
                );
            }
        });
    }

    /**
     * Helper generate ticket code
     */
    protected static function generateTicketCode($seminarId, $userId): string
    {
        return strtoupper(
            'TCK-' .
            $seminarId . '-' .
            $userId . '-' .
            now()->format('YmdHis') . '-' .
            Str::random(4)
        );
    }

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
    public function attendence()
    {
        return $this->hasOne(Attendence::class);
    }
}