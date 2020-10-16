<?php

class LicenceRenewalnvoiceLines extends \Eloquent {

    protected $table = 'LicenceRenewalnvoiceLines';
    protected $primaryKey = 'LiceneceRenewalInvoiceLineID';

    public $timestamps = false;

    public function id() { return $this->LiceneceRenewalInvoiceLineID; }
    public function service() { return $this->belongsTo('Service','ServiceID'); }
    public function invoice() { return $this->belongsTo('LiceneRenewaInvoice','InvoiceHeaderID'); }
    public function application() { return $this->belongsTo('LicenceRenewals','ServiceHeaderID'); }
}
