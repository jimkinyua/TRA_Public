<?php

class ForeignBusinessOwnersAttachements extends \Eloquent {


    protected $table = 'ForeignBusinessOwnersAttachements';

    protected $primaryKey = 'AttachementId';

    public function id()  {  return $this->AttachementId;  }

    public function Director() { return $this->belongsTo('Directors','DirectorsID');  }


}
