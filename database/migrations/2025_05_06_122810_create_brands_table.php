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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->string('url')->nullable();
            $table->string('primary_hex')->nullable();
            $table->boolean('is_visible')->default(false);
            // $table->string('logo')->nullable();
            // $table->string('website')->nullable();
            // $table->string('contact_email')->nullable();
            // $table->string('contact_phone')->nullable();
            // $table->string('address')->nullable();
            // $table->string('city')->nullable();
            // $table->string('state')->nullable();
            // $table->string('country')->nullable();
            // $table->string('postal_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
