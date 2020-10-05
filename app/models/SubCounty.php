<?php

class SubCounty extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'SubCounty';
    protected $primaryKey = 'SubCountyID';

    public function __toString(){
        return $this->SubCountyName;
    }

    public function id(){
        return $this->SubCountyID();
    }
}
