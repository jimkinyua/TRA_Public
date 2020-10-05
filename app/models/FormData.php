<?php


class FormData extends Eloquent{
    protected $table = 'FormData';

    protected $primaryKey = 'FormDataID';

    public $timestamps = false;

    public function id(){
        return $this->FormDataID;
    }

    public function __toString() {
        return $this->Value;
    }
}
