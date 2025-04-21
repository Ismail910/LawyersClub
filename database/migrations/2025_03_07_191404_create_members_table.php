<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('membership_number')->nullable();
            $table->string('name')->nullable();
            $table->string('job_title')->nullable();
            $table->date('membership_date')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('payment_voucher_number')->nullable();
            $table->year('last_payment_year')->nullable();
            $table->string('printing_status')->nullable();
            $table->text('notes')->nullable();
            $table->date('printing_and_payment_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('current_year_paid')->nullable();
            $table->boolean('voting_right')->default(false);
            $table->string('gender')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
