<?php


class Countries extends \Illuminate\Database\Eloquent\Model{

    protected $table = 'Countries';
    protected $primaryKey = 'Id';

    /**
     * @var bool
     */
    public $timestamps = false;

    public function __toString()
    {
        return $this->Name;
    }



    public function id(){
        return $this->Id;
    }
}