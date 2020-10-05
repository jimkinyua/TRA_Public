<?php

use Illuminate\Database\Eloquent\Model;

class CustomerAgent extends Model {

    protected $table = 'CustomerAgents';

    protected $primaryKey = 'CustomerAgentID';

    public $timestamps = false;

    protected $fillable = [ 'AgentRoleID', 'AgentID','CustomerID' ];

    public function id(){
        return $this->CustomerAgentID;
    }

}
