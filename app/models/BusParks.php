<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 2/28/2015
 * Time: 1:09 PM
 */

class County extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'BusParks';
    protected $primaryKey = 'ParkID';

    public function __toString(){
        return $this->ParkName;
    }

    public function id(){
        return $this->ParkID();
    }
}