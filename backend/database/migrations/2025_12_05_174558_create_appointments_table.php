<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_phone')->nullable();
            $table->date('booking_date')->index();
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', [
                'pending', 'confirmed', 'cancelled', 'completed'
            ])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['booking_date', 'start_time'], 'unique_time_slot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
