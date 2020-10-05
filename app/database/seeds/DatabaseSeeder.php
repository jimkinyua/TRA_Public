<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		#$this->call('UserTableSeeder');
		$this->call('BusinessTypesSeeder');
		$this->command->info('Seeded the biz types!');
		$this->call('CountriesSeeder');
		$this->command->info('Seeded the countries!');
	}

}
