<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['category_id', 'amount', 'notes','tenant_name'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function archivedBudget()
    {
        return $this->hasOne(ArchivedBudget::class, 'original_id');
    }
}

