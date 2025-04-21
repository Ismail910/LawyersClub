<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BudgetPrint extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'printed_at',
        'category_id',
        'amount',
        'notes',
    ];


    protected $casts = [
        'printed_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
