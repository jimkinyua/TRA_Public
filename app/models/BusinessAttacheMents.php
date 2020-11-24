<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 2/27/2019
 * Time: 11:40 AM
 */
//use Illuminate\Database\Eloquent\Model;

class BusinessAttacheMents extends \Illuminate\Database\Eloquent\Model 
{
    protected $table = 'BusinessAttachements';
    protected $primaryKey = 'BusinessRegistationDocID';
    public $timestamps = false;
    public function id()
    {
        return $this->BusinessRegistationDocID;
    }

    public function __toString()
    {
        return $this->DocumentPath;
    }
}