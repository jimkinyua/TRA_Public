<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 2/28/2015
 * Time: 1:28 PM
 */
use Illuminate\Database\Eloquent\Model;

class ServiceForm extends Eloquent{
    protected $table = 'Forms';

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
           

       return FormSection::where('FormID',$this->id())->orderBy('Priority','ASC')->get();
    //    echo '<pre>'; 
    //    print_r(FormSection::where('FormID',$this->id())->orderBy('Priority','ASC')->get());
    //    exit;
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