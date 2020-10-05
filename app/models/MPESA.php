<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 3/6/2015
 * Time: 1:02 PM
 */

class MPESA extends  \Eloquent{

  protected $table= 'Receipts';

    protected $primaryKey = 'ReceiptID';

   public function scopeFindByAccount($query,$account_no)
   {
       return $query->where('InvoiceHeaderID','=',$account_no);
   }

   public function id()
   {
       return $this->ReceiptID;
   }


}
