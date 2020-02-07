<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhinxlogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('phinxlog', function(Blueprint $table)
		{
			$table->bigInteger('version')->primary();
			$table->string('migration_name', 100)->nullable();
			$table->timestamp('start_time')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->dateTime('end_time')->default('0000-00-00 00:00:00');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('phinxlog');
	}

}
