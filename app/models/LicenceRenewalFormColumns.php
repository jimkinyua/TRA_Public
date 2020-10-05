<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 2/28/2020
 * Time: 12:17 PM
 */

class LicenceRenewalFormColumns extends \Illuminate\Database\Eloquent\Model {
    protected $table = 'LicenceRenewalColumns';

    protected $primaryKey = 'LicenceRenewalFormColumnID';

    public function id(){
        return $this->LicenceRenewalFormColumnID;
    }

    public function __toString()
    {
        return $this->FormColumnName;
    }


    public function dataType()
    {
        return $this->belongsTo('ColumnType','ColumnDataTypeID','ColumnDataTypeID');
    }
}