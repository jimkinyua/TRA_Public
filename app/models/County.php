<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 2/28/2015
 * Time: 1:09 PM
 */

class County extends \Illuminate\Database\Eloquent\Model{
    protected $table = 'CountyDetails';
    protected $primaryKey = 'CountyID';

    public function __toString(){
        return $this->CountyName;
    }

    public function id(){
        return $this->CountyID();
    }
}