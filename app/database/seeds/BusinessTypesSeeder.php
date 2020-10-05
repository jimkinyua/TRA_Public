<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class BusinessTypesSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        //Empty the countries table
        DB::table('BusinessType')->delete();

        //Get all of the countries
        $items = array(
            [
                'BusinessTypeName'=>'Type A',
                'CreatedDate'=>date('Y-m-d H:i:s'),
                'CreatedBy'=>0
            ],
            [
                'BusinessTypeName'=>'Type B',
                'CreatedDate'=>date('Y-m-d H:i:s'),
                'CreatedBy'=>0
            ],
            [
                'BusinessTypeName'=>'Type C',
                'CreatedDate'=>date('Y-m-d H:i:s'),
                'CreatedBy'=>0
            ],
            [
                'BusinessTypeName'=>'Type D',
                'CreatedDate'=>date('Y-m-d H:i:s'),
                'CreatedBy'=>0
            ],
            [
                'BusinessTypeName'=>'Type E',
                'CreatedDate'=>date('Y-m-d H:i:s'),
                'CreatedBy'=>0
            ],
            [
                'BusinessTypeName'=>'Type F',
                'CreatedDate'=>date('Y-m-d H:i:s'),
                'CreatedBy'=>0
            ]
        );
        foreach ($items as $biz){
            BusinessType::create($biz);
        }
    }
}
