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
        Schema::create('invoice_prints', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number');
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->timestamp('printed_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_prints');
    }
};

// sql command to create the table
// CREATE TABLE invoice_prints (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     serial_number VARCHAR(255) NOT NULL,
//     category_id INT NOT NULL,
//     invoice_number VARCHAR(255) NOT NULL,
//     amount DECIMAL(15, 2) NOT NULL,
//     description TEXT,
//     printed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,