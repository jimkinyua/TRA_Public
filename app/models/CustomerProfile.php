<?php

use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model {

    protected $table = 'CustomerProfile';

    protected $primaryKey = 'CustomerProfileID';

    public $timestamps = false;

    public function id(){
        return $this->CustomerProfileID;
    }

}
