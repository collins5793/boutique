// database/migrations/xxxx_xx_xx_xxxxxx_create_chatbot_responses_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_responses', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->text('answer');
            $table->json('keywords')->nullable();
            $table->string('response_type')->default('text'); // Pour gérer différents types de réponses
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_responses');
    }
};