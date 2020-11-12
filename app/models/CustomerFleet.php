<?php

class CustomerFleet extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'Fleets';
    protected $primaryKey = 'FleetId';

    public function __toString(){
        return $this->RegNo;
    }

    public function id(){
        return $this->FleetId;
    }
}