<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scraper_items', function (Blueprint $table) {
            $table->id();
            $table->string('domain_slug', 64)->index();
            $table->string('external_id', 255);
            $table->char('current_fingerprint', 32)->nullable();
            $table->string('status', 32)->default('active');
            $table->timestamp('first_seen_at');
            $table->timestamp('last_seen_at');
            $table->timestamp('last_changed_at')->nullable();
            $table->timestamps();

            $table->unique(['domain_slug', 'external_id'], 'scraper_items_domain_external_unique');
        });

        Schema::create('scraper_item_spider_metadata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('scraper_items')->onDelete('cascade');
            $table->string('spider_slug', 64);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['item_id', 'spider_slug'], 'scraper_item_spider_unique');
        });

        Schema::create('scraper_spider_runs', function (Blueprint $table) {
            $table->id();
            $table->string('domain_slug', 64)->index();
            $table->string('spider_slug', 64)->index();
            $table->string('status', 32)->default('running');
            $table->json('counters')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });

        Schema::create('scraper_domain_runs', function (Blueprint $table) {
            $table->id();
            $table->string('domain_slug', 64)->index();
            $table->string('status', 32)->default('running');
            $table->json('counters')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });

        Schema::create('scraper_item_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('scraper_items')->onDelete('cascade');
            $table->unsignedBigInteger('spider_run_id')->nullable()->index();
            $table->char('old_fingerprint', 32)->nullable();
            $table->char('new_fingerprint', 32)->nullable();
            $table->json('changes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('scraper_item_missing_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('scraper_items')->onDelete('cascade');
            $table->string('missing_cause', 64);
            $table->timestamp('missing_started_at');
            $table->timestamp('missing_ended_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scraper_item_missing_periods');
        Schema::dropIfExists('scraper_item_changes');
        Schema::dropIfExists('scraper_domain_runs');
        Schema::dropIfExists('scraper_spider_runs');
        Schema::dropIfExists('scraper_item_spider_metadata');
        Schema::dropIfExists('scraper_items');
    }
};
