<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A coluna "menu" é usada pelo site (AppServiceProvider e WebController),
     * mas não existia na migration original de posts. Em bases antigas ela foi
     * criada manualmente; esta migration regulariza instalações novas.
     */
    public function up(): void
    {
        if (Schema::hasTable('posts') && !Schema::hasColumn('posts', 'menu')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->integer('menu')->default(0);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('posts') && Schema::hasColumn('posts', 'menu')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropColumn('menu');
            });
        }
    }
};
