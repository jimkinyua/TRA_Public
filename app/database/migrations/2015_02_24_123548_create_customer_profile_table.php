<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerProfileTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customers_profile', function(Blueprint $table)
		{
			$table->increments('CustomerProfileID');
			$table->integer('CreatedBy');
			$table->boolean('CustomerProfileStatusID');
			$table->timestamp('CreatedDate');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('customers_profile');
	}

}
