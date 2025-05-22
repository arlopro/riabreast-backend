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
        Schema::create('rehab_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rehab_period_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('question');
            $table->enum('type', ['scale', 'text', 'choice'])->default('scale');
            $table->json('options')->nullable(); // per type = choice
            $table->json('labels')->nullable(); // per type = scale
            $table->json('block_if')->nullable(); // es: {"type": "scale", "greater_than": 6}
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rehab_questions');
    }
};
