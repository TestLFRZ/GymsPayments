<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_id')
                ->nullable()
                ->constrained('member_plan_subscriptions')
                ->nullOnDelete();
            $table->string('type');
            $table->string('title');
            $table->text('body')->nullable();
            $table->timestamp('scheduled_for');
            $table->timestamp('delivered_at')->nullable();
            $table->string('delivery_channel')->default('broadcast');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'scheduled_for']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
