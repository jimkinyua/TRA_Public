<?php

class MiscApplication extends Eloquent {

  protected $table = 'Miscellaneous';

  protected $primaryKey = 'ChargeID';

  public $timestamps = false;

  protected $fillable = ['CustomerName', 'Description', 'Amount','CreatedBy','CreatedDate', 'ServiceHeaderID' ];

  public function id()   {  return $this->ChargeID;    }

}
