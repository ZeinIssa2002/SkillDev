<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->json('placement_test')->nullable()->after('prerequisite_id');
            $table->unsignedTinyInteger('placement_pass_score')->nullable()->after('placement_test');
        });
        Schema::table('user_progress', function (Blueprint $table) {
            $table->boolean('placement_passed')->default(false)->after('completed');
        });
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['placement_test', 'placement_pass_score']);
        });
        Schema::table('user_progress', function (Blueprint $table) {
            $table->dropColumn('placement_passed');
        });
    }
};
