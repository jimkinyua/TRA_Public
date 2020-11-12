<?php

class Directors extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'Directors';
    protected $primaryKey = 'DirectorsID';

    public function __toString(){
        return $this->DirectorsID;
    }

    public function id(){
        return $this->DirectorsID;
    }
}