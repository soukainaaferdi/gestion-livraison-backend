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
        Schema::table('orders_statut', function (Blueprint $table) {
            //public function up()
{
    DB::statement("ALTER TABLE orders MODIFY COLUMN statut 
        ENUM('en_attente', 'assigne', 'livre', 'annule', 'retour') 
        DEFAULT 'en_attente'");
}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders_statut', function (Blueprint $table) {
            //
        });
    }
};
