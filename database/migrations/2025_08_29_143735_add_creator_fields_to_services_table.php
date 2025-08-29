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
        Schema::table('services', function (Blueprint $table) {
            $table->string('service_creator_name')->nullable()->after('service_desc');
            $table->string('service_creator_address')->nullable()->after('service_creator_name');
            $table->string('service_creator_phone_number')->nullable()->after('service_creator_address');
            $table->decimal('service_price', 10, 2)->nullable()->after('service_creator_phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'service_creator_name',
                'service_creator_address',
                'service_creator_phone_number',
                'service_price',
            ]);
        });
    }
};
