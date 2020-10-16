<?php

class Penalties extends \Eloquent {

    public $timestamps = false;

    protected $table = 'Penalties';

    protected $primaryKey = 'PenaltyID';

    public function id()  {  return $this->PenaltyID;  }

    public function receipt() { return $this->belongsTo('Services','ServiceID');  }


}
