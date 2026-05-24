<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('websites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->string('status')->default('unknown');
            $table->string('tech_stack')->nullable();
            $table->string('cms')->nullable();
            $table->timestamp('ssl_expires_at')->nullable();
            $table->foreignId('resource_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('website_environments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('environment')->default('production');
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_environments');
        Schema::dropIfExists('websites');
    }
};
