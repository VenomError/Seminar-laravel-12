<?php
namespace App\Models;

use App\Models\User;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{

    protected $table = 'attendances';
    protected $fillable = [
        'registration_id',
        'scanned_at',
        'scanned_by'
    ];

    protected $casts = [
        'scanned_at' => 'datetime'
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function scanner()
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }
}