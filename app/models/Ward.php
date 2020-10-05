<?php

class Ward extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'Wards';
    protected $primaryKey = 'WardID';

    public function __toString(){
        return $this->WardName;
    }

    public function id(){
        return $this->WardID();
    }
}
