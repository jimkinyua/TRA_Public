<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customers', function(Blueprint $table)
		{
			$table->increments('CustomerID');
			$table->string('CustomerName',100);
			$table->integer('CustomerProfileID');
			$table->string('ContactPerson');
			$table->string('Designation');
			$table->string('RegistrationNumber');
			$table->string('PIN');
			$table->string('VATNumber');
			$table->integer('BusinessTypeID');
			$table->string('PostalAddress');
			$table->string('PostalCode');
			$table->string('Town');
			$table->integer('CountryID');
			$table->string('Telephone1',30);
			$table->string('Telephone2',30);
			$table->string('Mobile1',30);
			$table->string('Mobile2',30);
			$table->string('Email');
			$table->string('Url');
			$table->timestamp('CreatedDate');
			$table->integer('CreatedBy');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('customers');
	}

}
