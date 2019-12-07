<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoverimageToPwebArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pweb_articles', function (Blueprint $table) {
            $table->string('coverimage', 255)->after('title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pweb_articles', function (Blueprint $table) {
			$table->dropColumn('ip_address');
        });
    }
}
