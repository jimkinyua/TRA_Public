<?php

class HireApplication extends Eloquent {

  protected $table = 'HireApplication';

  protected $primaryKey = 'HireApplicationID';

  public $timestamps = false;

  protected $fillable = [ 'Status', 'ToDate','FromDate','CreatedDate', 'ServiceHeaderID' ];
  
  public function id()   {  return $this->HireApplicationID;    }

}
