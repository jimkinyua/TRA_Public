<?php
use Illuminate\Support\Facades\Validator;
// session_start();

class AuthenticationController extends \BaseController {

    public function index(){
        return View::make('authentication.layout');
    }

    public function login(){
        
        $services = DB::table('FeaturedServices')->get(['Title', 'ShortDecsription']);
        return View::make('authentication.login', ['services' => $services]);
    }


    /**
     * @return mixed
     * Process user login request
     */
    public function postLogin(){
        
      $data = [];
      $rules = ['email'=> 'required', 'password'=>'required'];
      $validator = Validator::make(Input::all(), $rules);

      // $password='UG$CountyPay@20!9';
    //   $salt = Hash::make($password);
    //   dd('hapa');
          

      if ($validator->passes()){
          $credentials = ['Email'=>Input::get('email'), 'password'=>Input::get('password')];

          if (Auth::attempt($credentials))
          {
            # user agent authenticated. Pick default customer the agent shoud represent
            $user =  Auth::user();

            if($user) 
            {
              if($user->Active == false ) {
                Session::flash('error_msg','Account not activated');
                return Redirect::route('portal.login');
              }
              if ($user->ChangePassword){

                Session::flash('error_msg','Your Account Password was Reset Kindly Use
                 the Link Sent To Your Email To Log In');
                 return Redirect::route('portal.login');
                    //   Session::put('user__',$user->Email);

                  Auth::logout();

                  return Redirect::route('portal.login');
              }
              #return Redirect::intended(route('portal.dashboard'));
              Session::set('password', Input::get('password'));
              return Redirect::route('all.applications'); //('portal.services');
            }else{
              dd('Shhiida');
            }
          } else {
            $services = DB::table('FeaturedServices')->get(['Title', 'ShortDecsription']);
          
              Session::flash('error_msg','Invalid login Credentials');
              return Redirect::route('portal.login');
          }
      }else{
        $services = DB::table('FeaturedServices')->get(['Title', 'ShortDecsription']);
       

        return View::make('authentication.login',['services' => $services])
            ->withErrors($validator,'login' );
      }

     

    }

    /**
     * @return mixed
     * Load user profile view
     */
    public function profile(){
        $user = User::find(Auth::id());
        $usr = DB::table('UserProfile')
          #->select(['FirstName', 'MiddleName', 'LastName', 'IdNumber', 'Mobile', 'Email', 'CustomerProfileID', 'UserProfileID'])
          ->where('UserProfile.UserProfileID', Auth::id())
          ->get();
        dd($usr);
        Session::put('change_password_token',csrf_token());

        #return View::make('auth.reset',['user__'=>Session::get('user__'),'change_password_token'=>Session::get('change_password_token')]);
        return View::make('authentication.profile')->with('entity',$user);
    }


    public function viewProfile($id){
        $user = User::find($id);
        if (!$user){
            Session::flash('message','User not found');
            return View::make('layouts.404');
        }
        return View::make('security.profile')
            ->with('entity',$user);
    }
    /**
     * @return mixed applications
     *
     */
    protected function getPasswordRemindResponse()
    {
        return Password::remind(Input::only("email"));
    }


    protected function isInvalidUser($response)
    {
        return $response === Password::INVALID_USER;
    }

    /**
     * @param $token
     * @return mixed
     *
     */
    public function reset($token) {
        if ($this->isPostRequest()) {
            $credentials = Input::only(
                    "email",
                    "password",
                    "password_confirmation"
                ) + compact("token");

            $response = $this->resetPassword($credentials);

            if ($response === Password::PASSWORD_RESET) {
                return Redirect::route("user/profile");
            }

            return Redirect::back()
                ->withInput()
                ->with("error", Lang::get($response));
        }

        return View::make("user/reset", compact("token"));
    }

    /**
     * @param $credentials
     * @return mixed
     * Reset user password
     */
    protected function resetPassword($credentials) {
        return Password::reset($credentials, function($user, $pass) {
            $user->password = Hash::make($pass);
            $user->save();
        });
    }

    /**
     * @return mixed
     * Logout user
     */
    public function logout() {
        Auth::logout();
        unset($_SESSION);

        return Redirect::to(route('portal.login'));
    }

    /**
     * @return mixed
     * Show user registration form
     */
    public function getRegister(){
        $CountiesInKenya = Counties::select(['CountyName', 'CountyId'])->get();
        // echo '<pre>';
        // print_r($CountiesInKenya );
        // exit;

        return View::make('authentication.register', ['Counties'=>$CountiesInKenya]);
    }

    /**
     * @return mixed
     * Register user process
     */
    public function postRegister(){
      $rules = array(
          'Mobile1' => 'required|numeric|min:10|unique:Customer',
          'IDNO' => 'required|max:12|min:6|unique:Customer',
          'LastName' => 'required|max:255',
          'FirstName' => 'required|max:255',
          'MiddleName' => 'required|max:255',
          'email_confirmation' => 'same:email',
          'password' => 'required|confirmed|min:6',
          'email' => 'required|email|max:255|unique:Agents',
          'Town'=>'required',
          'County'=>'required|exists:Counties,CountyId',
      );

      $v = Validator::make(Input::all(),$rules);

      if ($v->passes()){
        $input = Input::all();

        $creds = array(
            'Active'=> 0,
            'ChangePassword' => 0,
            'Mobile'=>$input['Mobile1'],
            'Email' => $input['email'],
            'IDNO' => $input['IDNO'],
            'LastName' =>$input['LastName'],
            'password' => $input['password'],
            'FirstName' => $input['FirstName'],
            'MiddleName' =>$input['MiddleName'],
            'ConfirmationToken' => md5(uniqid(mt_rand(), true)),
            'County'=>$input['County'],
        );

        if ($this->register($creds)){

          $data['email'] = $creds['Email'];
          $data['EmailTitle'] = 'New Account Information';
          $data['subject'] = $data['EmailTitle'];
          $data['password'] = $creds['password'];
          $data['LastName'] = $creds['LastName'];
          $data['FirstName'] = $creds['FirstName'];
          $data['MiddleName'] = $creds['MiddleName'];
          $data['confirm_token'] = $creds['ConfirmationToken'];

          Api::sendMail('ActivateAccount', $data);

          Session::flash('success_msg',
            'User registration success. Please activate your account using the confirmation link sent to your email address');
          return Redirect::route('portal.get.register');

        } else {
            Session::flash('error_msg','Registration failure contact Support Team');
            return Redirect::route('portal.get.register')
                ->withInput(Input::except(array('password','password_confirmation')));
        }
      }
      return Redirect::route('portal.get.register')
          ->withErrors($v)
          ->withInput(Input::except(array('password','password_confirmation')));
    }

    /**
     * @return mixed
     * Show user registration form
     */
    public function getAddAccount(){
        return View::make('users.new');
    }

    /**
     * @return mixed
     * Register user process as logged in admin
     */
    public function postAddAccount(){
        #var_dump(Input::all());die();
        $rules = array(
            'Mobile' => 'required|max:15',
            'password' => 'required|min:6',
            'IDNumber' => 'required|max:12',
            'LastName' => 'required|max:255',
            'FirstName' => 'required|max:255',
            'MiddleName' => 'required|max:255',
            'email' => 'required|email|max:255|unique:Agents',
        );

        $v = Validator::make(Input::all(),$rules);

        if ($v->passes()){
            $data['email']=Input::get('email');
            $input = Input::all();
            $params = ['CreatedBy'=>0,'status'=>1];
            $id = Api::CustomerProfileID($params);
            $creds = array(
                'FirstName' => $input['FirstName'],
                'MiddleName' =>$input['MiddleName'],
                'LastName' =>$input['LastName'],
                'IDNumber' => $input['IDNumber'],
                'Mobile'=>$input['Mobile'],
                'Email' => $input['email'],
                'CustomerProfileID'=>$id,
                'password' => $input['password'],
                'ConfirmationToken' => '',
                'Active'=>1,
                'ChangePassword'=>1

            );


            if ($this->register($creds)){
                $data['FirstName'] = $creds['FirstName'];
                $data['LastName'] = $creds['LastName'];
                $data['MiddleName'] = $creds['MiddleName'];
                $data['password'] = $creds['password'];
                $data['EmailTitle'] = 'New Account Information';
                $data['subject'] = $data['EmailTitle'];
                Api::sendMail('NewAccount',$data);

                Session::flash('success_msg','User Account created successfully');
                return Redirect::route('list.users');
            }else{
                Session::flash('error_msg','Registration failure contact Support Team');
                return Redirect::route('add.user')
                    ->withInput(Input::except(array('password','password_confirmation')));
            }
        }
        return Redirect::route('add.user')
            ->withErrors($v)
            ->withInput(Input::except(array('password','password_confirmation')));
    }

    /**
     * @param $creds
     * @param bool $activate
     * @return bool
     * Register user helper function
     */
    public function register($creds){

      $salt = Hash::make($creds['password']);

        $agent = Agent::create([
          'password' => $salt,
          'IDNO' => $creds['IDNO'],
          'Email' => $creds['Email'],
          'Mobile'=> $creds['Mobile'],
          'Active'=>  $creds['Active'],
          'LastName' => $creds['LastName'],
          'FirstName' => $creds['FirstName'],
          'MiddleName' => $creds['MiddleName'],
          'ChangePassword' => $creds['ChangePassword'],
          'ConfirmationToken'=>$creds['ConfirmationToken'],
        ]);

        $customer = Customer::create([
          'Type' => 'individual',
          'CustomerName' => $agent,
          'IDNO' => $creds['IDNO'],
          'ContactPerson' => $agent,
          'Email' => $creds['Email'],
          'Mobile1'=> $creds['Mobile'],
          'BusinessZone'=>$creds['County'],
        ]);

        return CustomerAgent::create([
          'AgentRoleID' => 1,
          'AgentID'  => $agent->id(),
          'CustomerID' => $customer->id(),
        ]);

    }

    /**
     * @param $userId
     * @param $code
     * @return mixed
     * Activate user
     */
    public function activate($code){
        $user = Api::FindAgentBy('ConfirmationToken', $code);

        if ($user){
            if ($user->Active == 1){
                Session::flash('error_msg','Your account is already confirmed');
                return Redirect::route('portal.login');
            }else{
                if ($user->ConfirmationToken === $code){
                    $user->ConfirmationToken = '';
                    $user->Active = 1;
                    $user->save();

                    Session::flash('success_msg', 'Account Activation Successful Please login below.');
                    return Redirect::route('portal.login');
                }
            }
        }

        Session::flash('error_msg', 'Unable to activate user Try again later or contact Support Team.');
        return Redirect::route('portal.login');
    }

    public function toggleStatus($id){
        $user = User::find($id);
        if (!$user){
            Session::flash('error_msg','User not found');
            return Redirect::action('UsersController@index');
        }

        if ($user->active){
            $user->active = 0;
        }else{
            $user->active = 1;
        }
        $user->save();

        Session::flash('success_msg','User status changed successfully');
        return Redirect::action('UsersController@index');

    }

    public function getChangePassword()  {
        $user__ = Session::get('user__');
        Session::forget('user__');
        // exit('Hapa');
        Session::put('change_password_token', csrf_token());
        //dd(['user__'=>Session::get('user__'),'change_password_token'=>Session::get('change_password_token')]);
        
        // echo '<pre>';
        // print_r($user__);
        // exit;

        // dd($user__);
       

        return View::make('authentication.reset',['user__'=>$user__,'change_password_token'=>Session::get('change_password_token')]);
    }

    public function ResetUserPassWord($code){
        $user = Api::FindAgentBy('ConfirmationToken',$code);
        //print_r($user);die();
        if ($user){
            if ($user->ChangePassword){
                if ($user->ConfirmationToken === $code){
                    Session::put('user__',$user->Email);
                    $user->ConfirmationToken = '';
                    $user->save();
                    Session::put('change_password_token', csrf_token());
                    return View::make('authentication.reset',['user__'=>$user->Email,
                    'change_password_token'=>Session::get('change_password_token')]);
                   
                }
            }
        }

        Session::flash('error_msg', 'Unable to activate user Try again later or contact Support Team.');
        return Redirect::to('/login');
    }

    public function changePassword() {
        // exit(1);
        $token = Session::get('change_password_token');
        // echo '<pre>';
        // print_r(Input::all());
        // exit;

        $rules = array(
            'user__'=>'required|exists:Agents,Email',
            'change_password_token'=>"required|in:$token",
            'password'=>'required|min:6',
        );
        $messages = array(
            'in' => 'Invalid request token',
        );

       
        if(Input::get('email')) { 

            // echo '<pre>';
            // print_r(Input::get('email'));
            // exit;
          $rules = [ 'email' => 'required|exists:Agents,Email' ];
          $messages = [ 'email.exists' => 'Sorry We Could Not Find A Profile Under the Provided Email' ];
          $valid = Validator::make(Input::all(),$rules,$messages);
            // echo '<pre>';
            // print_r($valid);
            // exit;
          if($valid->passes()) 
          {
              //Send Email To Email with Rest Token
              $user = Agent::where('Email',Input::get('email'))->first();

              $user->ConfirmationToken = md5(uniqid(mt_rand(), true));
              $user->ChangePassword = true;
              if($user->save()){

                $data['FirstName'] = $user->FirstName;
                $data['email'] = $user->Email;
                $data['LastName'] = $user->LastName;
                $data['MiddleName'] = $user->MiddleName;
                $data['password'] = $user->password;
                $data['confirm_token'] = $user->ConfirmationToken;
                $data['EmailTitle'] = 'PassWord Reset';
                $data['subject'] = $data['EmailTitle'];
                Api::sendMail('PassWordReset',$data);
                
                Session::flash('message','Confrimation Email Sent Successfully');
                return Redirect::back();
              }
            Redirect::action('AuthenticationController@getChangePassword');
          }
         //   exit('invalid');
          return Redirect::action('AuthenticationController@getChangePassword')
              ->withErrors($valid);
        }
        // exit('Nooooma');
        $valid = Validator::make(Input::all(),$rules,$messages);
        if ($valid->passes())
        {
            
          
            $pass = Hash::make(Input::get('password'));

            //dd($pass);

            $user = Agent::where('Email',Input::get('user__'))->first();
            $user->password = $pass;
            $user->ChangePassword = false;
            $user->save();
            
            Session::flash('success_msg','You have successfully changed your password');
            return Redirect::route('portal.home');
        }else{
            return Redirect::action('AuthenticationController@getChangePassword')
            ->withErrors($valid);
        }
        
    }

    public function getUsersList(){
        $profileID = Auth::user()->CustomerProfileID;

        $users = User::where('CustomerProfileID','=',$profileID)->get();

        return View::make('users.index',['entities'=>$users]);
    }

    /**
     * Show profile of current logged in user
     * @return mixed
     */
    public function showMyProfile()  {
        $user = User::findOrFail(Auth::id());
        return View::make('auth.profile',['entity'=>$user]);
    }

    /**
     * Show user profile as per user
     * @param $id
     * @return mixed
     */
    public function showUserProfile($id) {
        $user = User::findOrFail($id);
        return View::make('auth.profile',['entity'=>$user]);
    }


}
