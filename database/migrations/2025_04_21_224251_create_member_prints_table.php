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

// sql command to create the table
// CREATE TABLE member_prints (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     serial_number VARCHAR(255) NOT NULL,
//     name VARCHAR(255) NOT NULL,
//     notes TEXT,
//     amount DECIMAL(15, 2) NOT NULL,
//     printed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
// );