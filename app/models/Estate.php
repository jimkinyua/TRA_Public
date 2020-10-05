<?php

use Illuminate\Database\Eloquent\Model;

class Estate extends Model {

    protected $table = 'Estates';

    protected $primaryKey = 'EstateID';

    public $timestamps = false;

    public function id(){
        return $this->EstateID;
    }

}
