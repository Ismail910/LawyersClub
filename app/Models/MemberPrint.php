<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberPrint extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'printed_at',
        'name',
        'notes',
        'amount',
    ];

    protected $casts = [
        'printed_at' => 'datetime',
    ];

}
