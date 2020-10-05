<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_profiles', function(Blueprint $table)
		{
			$table->increments('UserProfileID');
			$table->string('FirstName',100);
			$table->string('MiddleName',100);
			$table->string('LastName',100);
			$table->string('IDNumber',50);
			$table->string('Mobile',15);
			$table->string('Email',255)->unique();
			$table->string('password',255);
			$table->rememberToken();
			$table->integer('CustomerProfileID');
			$table->timestamp('CreatedDate');
			$table->integer('CreatedBy')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_profiles');
	}

}
