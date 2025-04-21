<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArchivedInvoice extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = false;
    protected $fillable = [
        'original_id',
        'category_id',
        'name',
        'invoice_number',
        'amount',
        'description',
        'archived_at',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function originalInvoice()
    {
        return $this->belongsTo(Invoice::class, 'original_id');
    }
}
