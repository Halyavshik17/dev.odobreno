<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Удаляем внешний ключ
            $table->dropForeign(['license_type_id']); // Замените на имя вашего ограничения
            
            // Удаляем столбец
            $table->dropColumn('license_type_id');
        });
    }

    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Восстанавливаем столбец
            $table->unsignedBigInteger('license_type_id')->after('phone');
        });
    }
};
