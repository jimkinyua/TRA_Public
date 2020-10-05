<?php

class LandApplication extends Eloquent {

  protected $table = 'LandApplication';

  protected $primaryKey = 'LandApplicationID';

  public $timestamps = false;

  protected $fillable = ['LRN', 'PlotNo', 'MPlotNo','TitleNo','CreatedDate', 'ServiceHeaderID' ];

  public function id()   {  return $this->LandApplicationID;    }

}
