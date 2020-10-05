<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 2/28/2015
 * Time: 1:37 PM
 */

class ColumnType extends \Illuminate\Database\Eloquent\Model  {
    protected $table = 'ColumnDataType';

    protected $primaryKey = 'ColumnDataTypeID';

    public function id(){
        return $this->ColumnDataTypeID;
    }

    public function __toString()
    {
        return $this->ColumnDataTypeName;
    }


}