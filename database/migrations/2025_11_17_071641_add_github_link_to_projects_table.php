<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Migration file
public function up()
{
    Schema::table('projects', function (Blueprint $table) {
        $table->string('github_link')->nullable()->after('deadline');
    });
}

public function down()
{
    Schema::table('projects', function (Blueprint $table) {
        $table->dropColumn('github_link');
    });
}
};
