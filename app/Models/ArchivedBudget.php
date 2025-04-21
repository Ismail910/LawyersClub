<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArchivedBudget extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;
    protected $fillable = ['original_id', 'category_id', 'amount', 'notes', 'archived_at', 'tenant_name'];

    public function originalBudget()
    {
        return $this->belongsTo(Budget::class, 'original_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
