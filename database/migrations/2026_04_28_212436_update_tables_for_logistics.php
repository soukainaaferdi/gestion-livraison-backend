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
        //
        Schema::table('livreurs', function (Blueprint $table) {
        $table->string('zone')->nullable(); // المنطقة (مثلا: Ain Sbaa)
        $table->decimal('solde', 10, 2)->default(0); // الفلوس اللي جمع
        $table->float('rating_avg')->default(5); // تقييم الليفرور
    });

    // 2. تحديث جدول الطلبيات
    Schema::table('orders', function (Blueprint $table) {
        $table->integer('priority')->default(1); // 1: عادي، 2: مهم، 3: مستعجل
        $table->string('destination_zone')->nullable(); // باش نقارنوها مع zone ديال الليفرور
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
