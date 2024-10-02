<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyVehiclesTable extends Migration
{
    public function up()
    {
        Schema::create('company_vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('vehicle_id'); // Добавляем vehicle_id
            // Другие поля, если необходимо
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade'); // Предполагаем, что есть таблица vehicles
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_vehicles');
    }
}