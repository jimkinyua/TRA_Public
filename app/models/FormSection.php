<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 2/28/2015
 * Time: 1:34 PM
 */
use Illuminate\Database\Eloquent\Model;

class FormSection extends Model{

    protected $table = 'FormSections';

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
        // print_r(FormColumns::where('FormSectionID',$this->id())
        // ->orderBy('Priority', 'ASC')
        // ->where('FormID',$this->FormID)
        // ->get());
        // exit;

        return FormColumns::where('FormSectionID',$this->id())
            ->orderBy('Priority', 'ASC')
            ->where('FormID',$this->FormID)
            ->get();
    }
}
