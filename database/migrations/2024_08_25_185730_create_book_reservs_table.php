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
        Schema::create('book_reservs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('book_id');
            $table->tinyInteger('status')->default(1)->comment('مشخص کننده فعال بودن یا نبودن رزرو است');
            $table->date('reserv_end_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_reservs');
    }
};
