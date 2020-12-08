<?php

use Illuminate\Database\Eloquent\Model;

class Customer extends Model {

    protected $table = 'Customer';

    protected $primaryKey = 'CustomerID';

    public $timestamps = false;

    protected $fillable = [
      'Type', 'CustomerName','IDNO', 'ContactPerson', 'Mobile1', 'PIN', 'BusinessID', 'PlotNo',
      'CustomerName', 'Ward', 'Email', 'Website', 'SubCounty', 'BusinessTypeID','ContactPerson',
      'PostalCode', 'Telephone1', 'Submitted', 'County', 'PostalAddress', 'BusinessRegistrationNumber', 'PhysicalAddress'
    ];

    public function id(){
        return $this->CustomerID;
    }

    public function __toString(){
  		$this->id = $this->CustomerID;

  		return $this->CustomerName;
  	}

}
