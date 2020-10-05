<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 2/28/2015
 * Time: 1:36 PM
 */

class FormColumns extends \Illuminate\Database\Eloquent\Model {
    protected $table = 'FormColumns';

    protected $primaryKey = 'FormColumnID';

    public function id(){
        return $this->FormColumnID;
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