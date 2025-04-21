<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'membership_number',
        'name',
        'job_title',
        'membership_date',
        'address',
        'phone',
        'payment_voucher_number',
        'last_payment_year',
        'printing_status',
        'notes',
        'printing_and_payment_date',
        'payment_date',
        'current_year_paid',
        'voting_right',
        'gender',
        'amount' 
    ];

    // Define the relationship with MembershipSection
    public function membershipSection()
    {
        return $this->belongsTo(MembershipSection::class);
    }

    // Scope to filter by membership_date
    public function scopeByMembershipDate($query, $startDate, $endDate)
    {
        return $query->whereBetween('membership_date', [$startDate, $endDate]);
    }

    // Scope to filter by printing_and_payment_date
    public function scopeByPrintingAndPaymentDate($query, $startDate, $endDate)
    {
        return $query->whereBetween('printing_and_payment_date', [$startDate, $endDate]);
    }

    // Scope to filter by payment_date
    public function scopeByPaymentDate($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
    }

    // Scope to filter by name (case insensitive search)
    public function scopeByName($query, $name)
    {
        return $query->where('name', 'like', "%$name%");
    }
}
