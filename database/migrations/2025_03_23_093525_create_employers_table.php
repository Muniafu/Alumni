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
        Schema::create('employers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->string('company_website');
            $table->string('company_email');
            $table->string('company_phone');
            $table->string('company_address');
            $table->string('company_city');
            $table->string('company_country');
            $table->string('company_postal_code');
            $table->string('company_logo')->nullable();
            $table->string('company_banner')->nullable();
            $table->string('company_linkedin')->nullable();
            $table->string('company_facebook')->nullable();
            $table->string('company_twitter')->nullable();
            $table->string('company_instagram')->nullable();
            $table->string('company_github')->nullable();
            $table->string('company_about');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employers');
    }
};
