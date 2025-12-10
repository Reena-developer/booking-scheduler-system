<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_special_days', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->enum('type', ['full_off', 'half_day', 'extra_hours'])->default('full_off');
            $table->enum('half_day_type', ['am', 'pm'])->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_special_days');
    }
};