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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id')->nullable();
            $table->foreign('hotel_id')
                ->references('id')->on('hotels')
                ->onDelete('cascade');
            $table->string('number');
            $table->string('type');
            $table->decimal('price', 8, 2);
            $table->string('status');
            $table->string('description')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign('hotel_id');
            $table->dropColumn('hotel_id');
        });
    }
};
