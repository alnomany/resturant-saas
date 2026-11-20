<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // كل "جلسة" = زيارة واحدة. visitor_id = الشخص الفريد (كوكي طويل)
        Schema::create('track_sessions', function (Blueprint $t) {
            $t->id();
            $t->uuid('visitor_id')->index();
            $t->unsignedBigInteger('user_id')->nullable()->index();
            $t->string('ip', 45)->nullable();
            $t->char('country', 2)->nullable()->index();
            $t->string('device', 10)->nullable();      // mobile / tablet / desktop
            $t->string('os', 30)->nullable();
            $t->string('browser', 30)->nullable();
            $t->string('source', 100)->default('direct')->index();   // google, snapchat, instagram, direct ...
            $t->string('medium', 100)->nullable();                   // cpc, paid, organic, social, referral
            $t->string('campaign', 150)->nullable()->index();
            $t->string('content', 150)->nullable();
            $t->string('term', 150)->nullable();
            $t->string('referrer', 255)->nullable();
            $t->string('landing_page', 300)->nullable();
            $t->timestamp('first_seen_at')->index();
            $t->timestamp('last_seen_at')->nullable();
        });

        // كل خطوة في الفانل = حدث
        Schema::create('track_events', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('session_id')->index();
            $t->uuid('visitor_id')->index();
            $t->unsignedBigInteger('user_id')->nullable();
            $t->string('name', 50);
            $t->json('meta')->nullable();
            $t->timestamp('created_at')->index();
            $t->index(['name', 'created_at']);
            $t->index(['session_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('track_events');
        Schema::dropIfExists('track_sessions');
    }
};
