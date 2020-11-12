<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 2/27/2015
 * Time: 11:43 AM
 */

class ServicePlus extends \Illuminate\Database\Eloquent\Model {
    protected $table = 'ServicePlus';

    protected $primaryKey = 'ServicePlusID';

    public function id(){
        return $this->ServicePlusID;
    }

    public function __toString()
    {
        return $this->ServicePlusID;
    }
}

?>