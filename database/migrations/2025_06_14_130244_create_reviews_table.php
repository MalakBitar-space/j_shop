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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // المستخدم الذي قام بالتقييم
            $table->foreignId('provider_id')->constrained('service_providers')->onDelete('cascade'); // مقدم الخدمة الذي يتم تقييمه
            $table->boolean('has_used_service')->default(false);
            $table->integer('rating')->default(1); // التقييم (من 1 إلى 5)
            $table->text('comment')->nullable(); // التعليق الاختياري
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
