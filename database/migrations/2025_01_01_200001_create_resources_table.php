<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('resource_type_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type')->nullable();
            $table->string('url')->nullable();
            $table->foreignId('owner_team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->string('status')->default('active');
            $table->timestamp('expires_at')->nullable();
            $table->decimal('cost_monthly', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('resource_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')->constrained()->cascadeOnDelete();
            $table->string('alert_type');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_alerts');
        Schema::dropIfExists('resources');
    }
};
