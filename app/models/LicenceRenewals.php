<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 3/4/2015
 * Time: 2:05 PM
 */

class LicenceRenewals extends Eloquent{
    protected $table = 'LicenceRenewals';

    protected $primaryKey = 'LicenceId';

    public $timestamps = false;

    public static $url = '/uploads/';

    public function id()
    {
        return $this->LicenceId;
    }

    public function attachments()
    {
        return $this->hasMany('ServiceDocument','LicenceId');
    }

    public function service()
    {
        return $this->belongsTo('Service','ServiceID');
    }
}
