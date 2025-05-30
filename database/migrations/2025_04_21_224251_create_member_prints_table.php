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
        Schema::create('member_prints', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
            $table->timestamp('printed_at')->useCurrent();
            $table->string('name')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_prints');
    }
};
