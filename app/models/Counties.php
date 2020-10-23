<?php

class Counties extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'Counties';
    protected $primaryKey = 'CountyId';

    public function __toString(){
        return $this->CountyName;
    }

    public function id(){
        return $this->primaryKey;
    }
}