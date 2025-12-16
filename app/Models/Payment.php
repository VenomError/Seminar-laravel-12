<?php
namespace App\Models;

use App\Enum\PaymentStatus;
use App\Models\User;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'registration_id',
        'amount',
        'proof_path',
        'status',
        'verified_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => PaymentStatus::class
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}