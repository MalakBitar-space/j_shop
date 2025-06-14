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
        Schema::create('publish_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('service_providers')->onDelete('cascade'); // مقدم الخدمة
            $table->timestamp('submitted_at')->useCurrent();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // حالة الطلب
            $table->text('notes')->nullable(); // ملاحظات المراجعة
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null'); // المراجع (مستخدم)
            $table->timestamp('reviewed_at')->nullable(); // وقت المراجعة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publish_requests');
    }
};
