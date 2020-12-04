<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 2/27/2015
 * Time: 3:54 PM
 */

class ServiceGroup extends Eloquent {

    protected $table = 'ServiceGroup';

    protected $primaryKey  = 'ServiceGroupID';

    public function categories(){
        return $this->hasMany('Category','ServiceGroupID');
    }

    public function primaryCategories(){
        return $this->hasMany('Category','ServiceGroupID')->where('PrimaryService', 1);
    }

   

    public function department()
    {
        return $this->belongsTo('Department','DepartmentID');
    }

    public function id(){
        return $this->ServiceGroupID;
    }

    public function __toString()
    {
        return $this->ServiceGroupName;
    }

    public function services()
    {
        return Service::where('ServiceGroupID',$this->id())->get();
    }

    public function form(){
        return $this->belongsTo('ServiceForm','FormID');
    }

}