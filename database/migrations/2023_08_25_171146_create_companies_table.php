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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('industry');
            $table->string('registration_number'); // Número de registro fiscal (CNPJ/RUC)
            $table->string('country'); // País de la empresa (Brasil/Paraguay)
            $table->string('address'); // Dirección de la empresa
            $table->string('city'); // Ciudad de la empresa
            $table->string('state'); // Estado o departamento de la empresa
            $table->string('postal_code'); // Código postal de la empresa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
