<?php

use Illuminate\Database\Eloquent\Model;

class Business extends Model{

    protected $table = 'Customer';
    /**
     * @var string
     */
    protected $primaryKey = 'CustomerID';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type(){
        return $this->belongsTo('BusinessType','BusinessTypeID');
    }

    public function country(){
        return $this->belongsTo('Country','CountryID');
    }


    public function scopeMyBusinesses($query,$profile)
    {
        return $query->where('CustomerProfileID',$profile);
    }

    public function __toString()
    {
        return $this->CustomerName;
    }
}