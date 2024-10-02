<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyLegalDocumentationsTable extends Migration
{
    public function up()
    {
        Schema::create('company_legal_documentations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('legal_documentation_id');
            // Другие поля, если необходимо
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('legal_documentation_id')->references('id')->on('legal_documentations')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_legal_documentations');
    }
}

