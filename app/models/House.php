<?php

use Illuminate\Database\Eloquent\Model;

class House extends Model {

    protected $table = 'Houses';

    protected $primaryKey = 'HouseID';

    public $timestamps = false;

    public function id(){
        return $this->HouseID;
    }

    public function scopeFromEstate($query,$estate)
    {
      return $query->where('ServiceID',$estate);
    }

}
