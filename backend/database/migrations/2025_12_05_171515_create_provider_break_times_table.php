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
        Schema::create('provider_break_times', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('day_of_week')->nullable(); 
            $table->enum('type', ['break', 'full_off', 'half_day', 'extra_hours'])->default('break');
            $table->enum('half_day_type', ['am', 'pm'])->nullable();
            $table->date('date')->nullable(); 
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('title')->nullable();
            $table->text('comment')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->index('type');
            $table->index('day_of_week');
            $table->index('date');
            $table->index(['day_of_week', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_break_times');
    }
};
