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
        Schema::create('rehab_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rehab_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('rehab_question_id')->constrained()->onDelete('cascade');
            $table->text('answer'); // risposta data (anche se è un numero, lo salviamo come testo)
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rehab_answers');
    }
};
