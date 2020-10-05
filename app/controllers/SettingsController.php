<?php

class SettingsController extends Controller {

  public function services() {
    return Redirect::route('settings.account');
  }

  public function account() {
    $user = User::find(Auth::id());
    $usr = DB::table('UserProfile')
      #->select(['FirstName', 'MiddleName', 'LastName', 'IdNumber', 'Mobile', 'Email', 'CustomerProfileID', 'UserProfileID'])
      ->where('UserProfile.UserProfileID', Auth::id())
      ->get();
    #dd($usr);
    Session::put('change_password_token',csrf_token());

    #return View::make('auth.reset',['user__'=>Session::get('user__'),'change_password_token'=>Session::get('change_password_token')]);
    return View::make('settings.account')->with('user',$user);
  }

}
