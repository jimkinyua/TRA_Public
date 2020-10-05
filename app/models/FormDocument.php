<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 3/5/2015
 * Time: 12:01 PM
 */

class FormDocument extends \Eloquent{
    protected $table = 'ServiceRequiredDocuments';

    protected $primaryKey = 'ServiceRequiredDocumentID';

    public function id()
    {
        return $this->ServiceRequiredDocumentID;
    }

    public function form()
    {
        return ServiceForm::where('FormID',$this->FormID)->first();
    }

    public function type()
    {
        return $this->belongsTo('DocumentType','DocumentTypeID');
    }

}