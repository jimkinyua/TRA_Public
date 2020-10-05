<?php


class LicenceRenewalFormData extends Eloquent{
    protected $table = 'LicenceRenewalFormData';

    protected $primaryKey = 'LiceneceRenewalFormDataId';

    public $timestamps = false;

    public function id(){
        return $this->LiceneceRenewalFormDataId;
    }

    public function __toString() {
        return $this->Value;
    }
}
