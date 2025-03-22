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
        Schema::table('reservations', function (Blueprint $table) {
            $table -> unsignedBigInteger('hotel_id') -> nullable() ->after('id');
            $table -> unsignedBigInteger('customer_id')-> nullable() ->after('hotel_id');
            $table -> unsignedBigInteger('room_id') -> nullable() ->after('customer_id');

            $table->foreign('hotel_id')
            ->references('id') -> on('hotels') ->onDelete('cascade');
            $table->foreign('customer_id')
            -> references('id') -> on ('customers')->onDelete('cascade');
            $table->foreign('room_id')
            -> references('id') -> on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign('hotel_id');
            $table->dropForeign('customer_id');
            $table->dropForeign('room_id');


            $table->dropColumn('hotel_id');
            $table->dropColumn('customer_id');
            $table->dropColumn('room_id');
        });
    }
};
