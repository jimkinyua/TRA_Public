<?php

class BusinessZone extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'BusinessZones';
    protected $primaryKey = 'ZoneID';

    public function __toString(){
        return $this->ZoneName;
    }

    public function id(){
        return $this->ZoneID();
    }
}
