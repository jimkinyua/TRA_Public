<?php

class LiceneRenewaInvoice extends \Eloquent {

    protected $table = 'LiceneRenewaInvoiceHeader';
    protected $primaryKey = 'LicenceRenewalInvoiceHeaderID';

    public $timestamps = false;
    public function id() { return $this->LicenceRenewalInvoiceHeaderID; }
    public function business() { return $this->belongsTo('Business','CustomerID'); }
    public function application() { return $this->belongsTo('ServiceHeader','InvoiceHeaderID'); }
    // public function payments() { return $this->hasMany('LicenceRenewalReceipt','LiceneRenewaInvoiceHeader'); }
    public function items() {  return $this->hasMany('LicenceRenewalnvoiceLines','InvoiceHeaderID'); }
    // public function items() {
    //     return DB::select('SELECT  [LiceneceRenewalInvoiceLineID]
    //     ,[InvoiceHeaderID]
    //     ,[Description]
    //     ,[Amount]
    //     FROM [TRANEW].[dbo].[LicenceRenewalnvoiceLines] WHere InvoiceHeaderID ='.$this->LicenceRenewalInvoiceHeaderID);  
    // }

    public function receipts() { return $this->hasMany('LicenceRenewaReceiptLines','InvoiceHeaderID'); }
	
    public function verifiedPayments() { return $this->hasMany('LicenceRenewalReceipt','LicenceRenewalInvoiceHeaderID')->where('ReceiptStatusID', 1); }

    public function paid(){	return $this->receipts->sum('Amount');
    }
    public function total(){ return $this->items->sum('Amount');
    }
     public function balance(){ return $this->total()- $this->paid();
    }
    public function receipted(){ return $this->receipts->sum('Amount');
    }

    public function recipient() 
	{		
		$sh = LicenceRenewaReceiptLines::where('InvoiceHeaderID', $this->id())->first();
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
