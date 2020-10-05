<?php

class Receipt_2 extends \Eloquent {

    protected $table = 'Receipts';
    protected $primaryKey = 'ReceiptID';

    public $timestamps = false;

    public function id() { return $this->ReceiptID; }
    //public function business() { return $this->belongsTo('Business','CustomerID'); }
    //public function payments() { return $this->hasMany('Receipt','InvoiceHeaderID'); }
    public function items() { return $this->hasMany('ReceiptLine','ReceiptID'); }    

    
}
