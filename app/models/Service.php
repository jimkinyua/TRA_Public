<?php
/**
 * Created by PhpStorm.
 * User: SSL
 * Date: 2/27/2015
 * Time: 11:43 AM
 */

class Service extends \Illuminate\Database\Eloquent\Model {

    protected $table = 'Services';
    protected $primaryKey = 'ServiceID';

    public $form = null;

    public function category(){
        return $this->belongsTo('Category','ServiceCategoryID');
    }

    public function department(){
        return $this->belongsTo('Department','DepartmentID');
    }

    public function charges() {
        return $this->hasMany('ServiceCharge', 'ServiceID');
    }

    public function currentCharges() {
        $currentYear = DB::table('FinancialYear')->where('isCurrentYear', 1)->pluck('FinancialYearId');
        return $this->charges()->where('FinancialYearId', $currentYear);
    }

    public function id()
    {
        return $this->ServiceID;
    }

    public function __toString()
    {
        return $this->ServiceName;
    }

    public function group()
    {
        return $this->belongsTo('ServiceGroup','ServiceGroupID');
    }

    public function __construct()
    {
        //$this->form = Form::find(@$this->group()->id());
    }
}