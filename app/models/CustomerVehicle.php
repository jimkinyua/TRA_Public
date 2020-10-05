<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 2/28/2015
 * Time: 1:09 PM
 */

class CustomerVehicle extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'CustomerVehicles';
    protected $primaryKey = 'VehicleID';

    public function __toString(){
        return $this->RegNo;
    }

    public function id(){
        return $this->VehicleID();
    }
}