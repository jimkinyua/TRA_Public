<?php


class Country extends \Illuminate\Database\Eloquent\Model{

    protected $table = 'Country';

    public function __toString()
    {
        return $this->CountryName;
    }
}