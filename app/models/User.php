<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'Agentds';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	//protected $hidden = array('password', 'remember_token');

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['FirstName', 'Email', 'password','IDNumber','CreatedBy'
		,'Mobile','MiddleName','LastName','CustomerProfileID',
		'ConfirmationToken'];

	/**
	 * Change the default primary key column name
	 * @var string
	 */
	protected $primaryKey = 'UserProfileID';

	/**
	 * Stop laravel from automatically updating timestamps
	 * @var bool
	 */
	public $timestamps = false;

	public $id;

	public function __toString(){
		$this->id = $this->UserProfileID;

		return $this->LastName.', '.$this->FirstName.' '.$this->MiddleName;
	}

	public function id(){
		return $this->UserProfileID;
	}

	public function customerID(){
		return DB::table('Customer')
			->where('UserProfileID', $this->UserProfileID)
			->pluck('CustomerID');
	}

	public function businesses()
	{
		return Business::where('');
	}

}
