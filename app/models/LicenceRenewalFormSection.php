<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 2/28/2015
 * Time: 1:34 PM
 */
use Illuminate\Database\Eloquent\Model;

class LicenceRenewalFormSection extends Model{

    protected $table = 'LicenceRenewalFormSection';

    protected $primaryKey = 'FormSectionID';

    public $timestamps = false;

    public function id(){
        return $this->FormSectionID;
    }

    public function __toString()
    {
        return $this->FormSectionName;
    }


    public function columns()
    {

        // echo '<pre>';
        // print_r(LicenceRenewalFormColumns::where('FormSectionID',$this->id())
        // ->orderBy('Priority', 'ASC')
        // ->where('LicenceRenewalFormID',2)
        // ->get());
        // exit;

        return LicenceRenewalFormColumns::where('FormSectionID',$this->id())
            ->orderBy('Priority', 'ASC')
            ->where('LicenceRenewalFormID',2)
            ->get();
    }
}
