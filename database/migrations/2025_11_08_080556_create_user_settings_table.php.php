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
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Profile Settings
            $table->string('full_name')->nullable();
            $table->string('phone')->nullable();
            $table->text('bio')->nullable();

            // Preferences
            $table->string('language')->default('en');
            $table->string('theme')->default('light');
            $table->integer('items_per_page')->default(25);
            $table->string('timezone')->default('Asia/Jakarta');

            // Notifications
            $table->boolean('email_notifications')->default(true);
            $table->boolean('push_notifications')->default(true);
            $table->boolean('task_reminders')->default(true);
            $table->boolean('project_updates')->default(true);
            $table->boolean('team_notifications')->default(true);

            // Privacy
            $table->enum('profile_visibility', ['public', 'team', 'private'])->default('team');
            $table->boolean('show_email')->default(false);
            $table->boolean('show_activity')->default(true);
            $table->boolean('show_online_status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
