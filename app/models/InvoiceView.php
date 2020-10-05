<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 3/5/2015
 * Time: 2:40 PM
 */

class InvoiceView extends \Eloquent{

    protected $table = 'vwInvoices';

    protected $primaryKey = 'ServiceHeaderID';

    public function id()
    {
        return $this->ServiceHeaderID;
    }
}
