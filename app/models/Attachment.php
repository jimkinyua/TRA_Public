<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 2/27/2019
 * Time: 11:40 AM
 */
//use Illuminate\Database\Eloquent\Model;

class Attachments extends \Illuminate\Database\Eloquent\Model 
{
    protected $table = 'Attachments';
    protected $primaryKey = 'ID';
    public function id()
    {
        return $this->ID;
    }

    public function __toString()
    {
        return $this->FilePath;
    }
}