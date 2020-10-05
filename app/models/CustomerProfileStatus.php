<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 2/27/2015
 * Time: 9:35 AM
 */

class CustomerProfileStatus extends \Illuminate\Database\Eloquent\Model {
    protected $table = 'CustomerProfileStatus';
    protected $primaryKey = 'CustomerProfileStatusID';

    public $id;

    public function __toString()
    {
        $this->id = $this->CustomerProfileStatusID;
        return $this->CustomerProfileName;
    }

    public function id(){
        return $this->CustomerProfileStatusID;
    }

}