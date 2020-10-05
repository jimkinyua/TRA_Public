<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 2/28/2015
 * Time: 1:28 PM
 */
use Illuminate\Database\Eloquent\Model;

class LicenceRenewalForm extends Eloquent{
    protected $table = 'LicenenceRenewalForm';

    protected $primaryKey = 'FormID';

    public $id;

    public function id(){
        return $this->FormID;
    }

    public function __toString(){
        return $this->FormName;
    }

    public function sections()
    {
    //           echo '<pre>'; 
    //    print_r($this->FormID);
    //    exit;

       return LicenceRenewalFormSection::where('LicenceRenewalFormID',$this->id())->orderBy('Priority','ASC')->get();
    
    }

    public function __construct()
    {
        $this->id =  $this->FormID;
    }

    public function documents()
    {
        return $this->hasMany('FormDocument','FormID');
    }
}