<?php

class LicenceRenewaReceiptLines extends \Eloquent {

    public $timestamps = false;

    protected $table = 'LicenceRenewaReceiptLines';

    protected $primaryKey = 'LicenceRenewalnvoiceLinesID';

    public function id()  {  return $this->LicenceRenewalnvoiceLinesID;  }

    public function receipt() { return $this->belongsTo('Receipt','ReceiptID');  }

    protected $fillable = ['CreatedBy', 'Amount', 'ReceiptID','CreatedDate','InvoiceHeaderID', 'BankID', 'ReceiptStatusID'  ];
	
	public function recipient() {
	  $ihid=0;
	  $ih=ReceiptLine::where ('InvoiceHeaderID',$this->id())->first();
	  if(!is_null($ih)){
		  $ihid=$ih->InvoiceHeaderID;
	  }
	  $sh = InvoiceLine::where('InvoiceHeaderID', $ihid)->first();
	  if(!is_null($sh)) {
		$shid = $sh->ServiceHeaderID;
		$misc = MiscApplication::where('ServiceHeaderID', $shid)->first();
		if(!is_null($misc)) { return $misc->CustomerName; }
	  }
	  $CustID='';
	  $Applic=Application::where('ServiceHeaderID',$shid)->first();
	  if(!is_null($Applic)){
		  $CustID=$Applic->CustomerID;
	  }
	  
	  //$ac = $this->belongsTo('Business','CustomerID')->first();
	  $ac = Business::where('CustomerID', $CustID)->first();
	  if(!is_null($ac)) 
	  {
		  return $ac->CustomerName; 
	  }else{
		  return 'Customer Name';
	  }
	  return $this->business;
	}
}
