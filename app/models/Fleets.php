<?php

class Fleets extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'Fleets';
    protected $primaryKey = 'FleetId';

    public function __toString(){
        return $this->FleetId;
    }

    public function id(){
        return $this->FleetId;
    }
}