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
        Schema::create('budget_prints', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
            $table->timestamp('printed_at')->useCurrent();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_prints');
    }
};

// sql command to create the table
    // CREATE TABLE budget_prints (
    //     id INT AUTO_INCREMENT PRIMARY KEY,
    //     serial_number VARCHAR(255) NOT NULL,
    //     category_id INT NOT NULL,
    //     amount DECIMAL(15, 2) NOT NULL,
    //     notes TEXT,
    //     printed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    //     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    // );