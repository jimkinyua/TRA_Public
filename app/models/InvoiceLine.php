<?php

class InvoiceLine extends \Eloquent {

    protected $table = 'InvoiceLines';
    protected $primaryKey = 'InvoiceLineID';

    public $timestamps = false;

    public function id() { return $this->InvoiceLineID; }
    public function service() { return $this->belongsTo('Service','ServiceID'); }
    public function invoice() { return $this->belongsTo('Invoice','InvoiceHeaderID'); }
    public function application() { return $this->belongsTo('Application','ServiceHeaderID'); }
}
