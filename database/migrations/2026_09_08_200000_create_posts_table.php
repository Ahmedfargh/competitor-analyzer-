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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->nullable()->index();
            $table->foreignId('author_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->json('title');
            $table->string('slug')->index();
            $table->string('type')->default('post')->index(); // 'post', 'landing_page'
            $table->string('status')->default('draft')->index(); // 'draft', 'published'
            $table->json('excerpt')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('blocks')->nullable();
            $table->json('seo_meta')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->unique(['tenant_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
