<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoicePrint extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'category_id',
        'invoice_number',
        'amount',
        'description',
        'printed_at',
    ];

    protected $casts = [
        'printed_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
