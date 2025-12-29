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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');          // Kharcha kis cheez ka hai (e.g., Pizza)
            $table->integer('amount');        // Kitne rupaye (e.g., 500)
            $table->string('category');       // Food, Travel, etc.
            $table->date('date');             // Kab kharch kiya
            $table->timestamps();             // Created at time and Updated at time
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
