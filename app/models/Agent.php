<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Agent extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'Agents';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	#protected $hidden = array('Password', 'RememberToken');

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
   protected $fillable = [ 'FirstName', 'MiddleName','LastName', 'Email', 'Active',
    'password','IDNO', 'Mobile', 'ChangePassword', 'Telephone', 'ConfirmationToken' ];

	/**
	 * Change the default primary key column name
	 * @var string
	 */
	protected $primaryKey = 'AgentID';

	/**
	 * Stop laravel from automatically updating timestamps
	 * @var bool
	 */
	public $timestamps = false;

	public $id;

	public function __toString(){
		$this->id = $this->AgentID;

		return $this->LastName.' '.$this->FirstName.' '.$this->MiddleName;
	}

	public function id(){
		return $this->AgentID;
	}

}
