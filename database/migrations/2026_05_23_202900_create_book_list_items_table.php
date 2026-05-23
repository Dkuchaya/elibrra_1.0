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
       Schema::create('book_list_items', function (Blueprint $table) {
    $table->id();

    $table->foreignId('book_list_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('book_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->integer('sort_order')->default(0);

    $table->timestamps();

    $table->unique(['book_list_id', 'book_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_list_items');
    }
};
