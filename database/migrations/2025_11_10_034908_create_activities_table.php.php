<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('type'); // 'task_created', 'task_updated', 'comment_added', etc.
            $table->string('description');
            $table->json('metadata')->nullable(); // Additional data
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('project_id');
            $table->index('task_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
