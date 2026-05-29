<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('denumire');
            $table->string('localitate');
            $table->string('adresa')->nullable();
            $table->foreignId('route_id')->nullable()->constrained('routes')->nullOnDelete();
            $table->enum('tip', ['magazin', 'angro'])->default('magazin');
            $table->string('token_acces', 64)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
