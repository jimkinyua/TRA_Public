<?php

class HouseApplication extends Eloquent {

    protected $table = 'HouseApplication';

    protected $primaryKey = 'HouseApplicationID';

    public $timestamps = false;

    protected $fillable = ['HouseNumber', 'EstateID','Status','CreatedDate', 'ServiceHeaderID' ];

    public function id()   {  return $this->HouseApplicationID;    }
}
