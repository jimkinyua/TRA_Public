<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 2/28/2015
 * Time: 1:09 PM
 */

class Inspections extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'Inspections';
    protected $primaryKey = 'InspectionID';

    public function __toString(){
        return $this->CountyName;
    }

<<<<<<< HEAD
    public function id(){
        return $this->InspectionID;
    }
=======
    // public function id(){
    //     return $this->InspectionID();
    // }
    public function id()   {  return $this->InspectionID;    }
>>>>>>> master
}