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
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('quantity');
            $table->integer('price');
            $table->integer('total');
            $table->enum('type', ['tablet', 'injection', 'Syrup']); // update code with that
            $table->enum('status', ['used', 'new']); // update code with that
            $table->string('medication_img')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->date('expiry_date')->nullable(); // update code with that
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
