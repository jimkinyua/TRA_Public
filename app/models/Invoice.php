<?php

class Invoice extends \Eloquent {

    protected $table = 'InvoiceHeader';
    protected $primaryKey = 'InvoiceHeaderID';

    public $timestamps = false;

    public function id() { return $this->InvoiceHeaderID; }
    public function business() { return $this->belongsTo('Business','CustomerID'); }
    public function application() { return $this->belongsTo('ServiceHeader','InvoiceHeaderID'); }
    public function payments() { return $this->hasMany('Receipt','InvoiceHeaderID'); }
    public function items() { return $this->hasMany('InvoiceLine','InvoiceHeaderID'); }
    public function receipts() { return $this->hasMany('ReceiptLine','InvoiceHeaderID'); }
	
    public function verifiedPayments() { return $this->hasMany('Receipt','InvoiceHeaderID')->where('ReceiptStatusID', 1); }

    public function paid(){	return $this->receipts->sum('Amount');}
    public function total(){ return $this->items->sum('Amount');}
    public function balance(){ return $this->total()- $this->paid();}
    public function receipted(){ return $this->receipts->sum('Amount');}

    public function recipient() 
	{		
		$sh = InvoiceLine::where('InvoiceHeaderID', $this->id())->first();
		if(!is_null($sh)) 
		{
			$shid = $sh->ServiceHeaderID;
			$misc = MiscApplication::where('ServiceHeaderID', $shid)->first();
			if(!is_null($misc)) { return $misc->CustomerName; }
		}
		$ac = $this->belongsTo('Business','CustomerID')->first();
		if(!is_null($ac)) 
		{
			return $ac->CustomerName; 
		}
		return $this->business;
    }
}
