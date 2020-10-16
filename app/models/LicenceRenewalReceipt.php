
<?php

class LicenceRenewalReceipt extends  \Eloquent{

	protected $table= 'LicenceRenewalReceipt';
	protected $dates = ['ReceiptDate'];
	protected $primaryKey = 'LicenceRenewalReceiptID';
  
	public function id() { return $this->LicenceRenewalReceiptID; }
	public function business() { return $this->belongsTo('Business','CustomerID'); }
	public function items() { return $this->hasMany('LicenceRenewaReceiptLines','ReceiptID'); } 
  
	protected $fillable = [
	'ReferenceNumber', 'InvoiceHeaderID', 'Amount','CreatedBy','ReceiptDate','ReceiptMethodID', 'BankID', 'ReceiptStatusID'
	];

	public $timestamps = false;

	public function recipient() {
	  $ihid=0;
	  $ih=LicenceRenewaReceiptLines::where ('LicenceRenewalReceiptID',$this->id())->first();
	  if(!is_null($ih)){
		  $ihid=$ih->InvoiceHeaderID;
	  }
	  $sh = LiceneRenewaInvoiceHeader::where('LicenceRenewalInvoiceHeaderID', $ihid)->first();
	  if(!is_null($sh)) {
		$shid = $sh->ServiceHeaderID;
		$misc = MiscApplication::where('ServiceHeaderID', $shid)->first();
		if(!is_null($misc)) { return $misc->CustomerName; }
	  }
	  $CustID='';
	  $Applic=LicenceRenewals::where('ServiceHeaderID',$shid)->first();
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
