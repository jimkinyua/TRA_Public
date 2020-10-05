<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 3/5/2015
 * Time: 12:10 PM
 */

class ServiceDocument extends \Eloquent {

    protected $table = 'ServiceDocuments';

    protected $primaryKey = 'ServiceDocumentID';

    public $timestamps = false;

    public function id()
    {
        return $this->ServiceDocumentID;
    }

    public function __toString()
    {
        return $this->ServiceDocumentName;
    }


}