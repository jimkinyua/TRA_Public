<?php

class ServiceCharge extends \Illuminate\Database\Eloquent\Model {

    protected $table = 'ServiceCharges';
    protected $primaryKey = 'ChargeID';

    public function id()
    {
        return $this->ChargeID;
    }

    public function __toString()
    {
        return $this->Amount ? $this->Amount : '0';
    }
}