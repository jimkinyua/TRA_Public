<?php

class Permits extends \Eloquent {

    public $timestamps = false;

    protected $table = 'Permits';

    protected $primaryKey = 'id';

    public function id()  {  return $this->id;  }

    public function Service() { return $this->belongsTo('ServiceHeader','ServiceHeaderID');  }


}
