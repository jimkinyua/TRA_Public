<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 2/27/2015
 * Time: 11:40 AM
 */

class Department extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'Departments';

    protected $primaryKey = 'DepartmentID';

    public function groups(){
        return $this->hasMany('ServiceGroup','DepartmentID');
    }

    public function services(){
        return $this->hasMany('Service','DepartmentID');
    }

    public function id()
    {
        return $this->DepartmentID;
    }

    public function __toString()
    {
        return $this->DepartmentName;
    }
}