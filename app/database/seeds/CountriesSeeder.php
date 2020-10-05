<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class CountriesSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        //Empty the countries table
        DB::table('Country')->delete();

        //Get all of the countries
        $countries = Countries::getList();
        foreach ($countries as $countryId => $country){
            DB::table('Country')->insert(array(
                'CountryName' => $country['name'],
                'CreatedDate'=>date('Y-m-d H:i:s')
            ));
        }
    }
}
