<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 2/27/2015
 * Time: 11:41 AM
 */

class Category extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'ServiceCategory';
    protected $primaryKey = 'ServiceCategoryID';


    public function department(){
        return $this->belongsTo('Department','DepartmentID');
    }

    public function services(){
        return $this->hasMany('Service','ServiceCategoryID');
    }
    public function id()
    {
        return $this->ServiceCategoryID;
    }

    public function __toString(){
        return $this->CategoryName;
    }

    public function myServices()
    {
        $sers = Service::where('ServiceCategoryID',$this->id())->get();


        return $sers;
    }

}