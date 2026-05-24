<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('service');
            $table->string('label');
            $table->text('api_key_encrypted')->nullable();
            $table->string('api_key_hint')->nullable();
            $table->decimal('quota_limit', 14, 2)->nullable();
            $table->decimal('quota_used', 14, 2)->default(0);
            $table->date('billing_date')->nullable();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('ai_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_account_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 14, 4)->default(0);
            $table->string('unit')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('logged_at');
            $table->timestamps();
        });

        Schema::create('ai_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_account_id')->constrained()->cascadeOnDelete();
            $table->string('alert_type');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_alerts');
        Schema::dropIfExists('ai_usage_logs');
        Schema::dropIfExists('ai_accounts');
    }
};
