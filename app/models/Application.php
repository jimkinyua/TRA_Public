<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 3/4/2015
 * Time: 2:05 PM
 */

class Application extends Eloquent{
    protected $table = 'ServiceHeader';

    protected $primaryKey = 'ServiceHeaderID';

    public $timestamps = false;

    public static $url = '/uploads/';

    public function id()
    {
        return $this->ServiceHeaderID;
    }

    public function attachments()
    {
        return $this->hasMany('ServiceDocument','ServiceHeaderID');
    }

    public function service()
    {
        return $this->belongsTo('Service','ServiceID');
    }
}
