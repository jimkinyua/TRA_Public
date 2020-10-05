<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('business_types', function(Blueprint $table)
		{
			$table->increments('BusinessTypeID');
			$table->string('BusinessTypeName');
			$table->timestamp('CreatedDate');
			$table->integer('CreatedBy');
			$table->text('Notes');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('business_types');
	}

}
