<?php

class Receipt extends  \Eloquent{

	protected $table= 'Receipts';
	protected $dates = ['deleted_at'];
	protected $primaryKey = 'ReceiptID';
  
	public function id() { return $this->ReceiptID; }
	public function business() { return $this->belongsTo('Business','CustomerID'); }
	public function items() { return $this->hasMany('ReceiptLine','ReceiptID'); } 
  
	protected $fillable = [
	'ReferenceNumber', 'InvoiceHeaderID', 'Amount','CreatedBy','ReceiptDate','ReceiptMethodID', 'BankID', 'ReceiptStatusID'
	];

	public $timestamps = false;

	public function recipient() {
	  $ihid=0;
	  $ih=ReceiptLine::where ('ReceiptID',$this->id())->first();
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
