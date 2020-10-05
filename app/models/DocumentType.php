<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 3/5/2015
 * Time: 12:01 PM
 */

class DocumentType extends \Eloquent {

    protected $table = 'DocumentTypes';

    protected $primaryKey = 'DocumentTypeID';

    public function id()
    {
        return $this->DocumentTypeID;
    }

    public function __toString()
    {
        return $this->DocumentTypeName;
    }
}