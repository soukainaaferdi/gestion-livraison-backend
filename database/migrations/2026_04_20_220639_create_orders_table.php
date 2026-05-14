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
        Schema::create('orders', function (Blueprint $table) {
        $table->id();
        // الربط بجدول clients اللي تكريا قبل
        $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
        $table->string('produit');
        $table->decimal('prix_total', 8, 2);
        $table->string('destination');
        $table->enum('statut', ['en_attente', 'assigne', 'livre', 'annule','retour'])->default('en_attente');
        $table->foreignId('livreur_id')->nullable()->constrained('livreurs')->onDelete('set null');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
