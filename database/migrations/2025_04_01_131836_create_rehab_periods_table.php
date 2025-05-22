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
        Schema::create('rehab_periods', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order')->default(1);
            $table->unsignedInteger('p_number')->nullable();
            $table->string('title'); // es: "Periodo 1"
            $table->text('description')->nullable();
            $table->string('video_youtube_id');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rehab_periods');
    }
};
