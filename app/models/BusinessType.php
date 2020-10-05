<?php

use Illuminate\Database\Eloquent\Model;

class BusinessType extends Model {

    protected $table = 'BusinessType';

    protected $primaryKey = 'BusinessTypeID';

    public $timestamps = false;

    public function __toString()
    {
        return $this->BusinessTypeName;
    }

}
