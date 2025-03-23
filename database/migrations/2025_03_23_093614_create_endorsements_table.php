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
        Schema::create('endorsements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('profile_id')->constrained()->onDelete('cascade');
            $table->string('comment');
            $table->integer('rating');
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_rejected')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->foreignId('rejected_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('endorsed_at')->nullable();
            $table->timestamp('endorsed_until')->nullable();
            $table->string('endorsed_by');
            $table->string('endorsed_to');
            $table->string('endorsed_relation');
            $table->string('endorsed_relation_description');
            $table->string('endorsed_relation_proof');
            $table->string('endorsed_relation_proof_description');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('endorsements');
    }
};
