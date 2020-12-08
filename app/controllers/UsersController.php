<?php

use BotDetectCaptcha\LaravelCaptcha\BotDetectLaravelCaptcha;

class UsersController extends \BaseController {

    /**
     * @return mixed
     * Show user login form
     */
    public function getLoginForm(){
        return View::make('auth.login');
    }

    //return a list of users
    public  function index()
    {

    }


    /**
     * @return mixed
     * Process user login request
     */
    public function postLogin(){
        $data = [];
        $rules = array('email'=>'required','password'=>'required');
        $validator = Validator::make(Input::all(),$rules);

        if ($validator->passes())
        {
            $credentials = array('Email'=>Input::get('email'),
                'password'=>Input::get('password')
            );

            $var = Api::FindUserBy('email',Input::get('email'));
            $hashed = Hash::make(Input::get('password'));
            $data = [$var,$hashed];
           // return Response::json($data);


            if (Auth::attempt($credentials)){
                $user =  User::find(Auth::user()->UserProfileID);

                //dd($user);
                //if($user->Active == false) {
                //  Session::flash('error_msg','Account not activated');
                //  return Redirect::to('login');
                //}
                if ($user->ChangePassword){
                    Session::put('user__',$user->Email);

                    Auth::logout();

                    return Redirect::route('get.change.password');
                }
                return Redirect::intended('dashboard');
            }else{
                Session::flash('error_msg','Invalid login credentials or account not activated');
                return Redirect::to('login');
            }
        }

        //$data['error'] = "Username and/or Password is invalid";
        return View::make('auth.login')
            ->withErrors($validator);

    }

    /**
     * @return mixed
     * Load user profile view
     */
    public function profile(){
        $user = User::find(Auth::id());
        return View::make('auth.profile')
            ->with('entity',$user);
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
     * @return mixed
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
    public function reset($token)
    {
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
    protected function resetPassword($credentials)
    {
        return Password::reset($credentials, function($user, $pass) {
            $user->password = Hash::make($pass);
            $user->save();
        });
    }

    /**
     * @return mixed
     * Logout user
     */
    public function logout()
    {
        Auth::logout();
        unset($_SESSION);

        return Redirect::to("/");
    }

    /**
     * @return mixed
     * Show user registration form
     */
    public function getRegistrationForm(){

      return View::make('auth.register');
    }



    /**
     * @return mixed
     * Register user process
     */
    public function postRegister(){
        $code = Input::get('CaptchaCode');
        $rules = array(
            'FirstName' => 'required|alpha|max:255',
            'MiddleName' => 'required|alpha|max:255',
            'LastName' => 'required|alpha|max:255',
            'Mobile' => 'required|numeric',
            'IDNumber' => 'required|numeric',
            'email' => 'required|email|max:255|unique:UserProfile',
            'email_confirmation' => 'same:email',
            'password' => 'required|confirmed|min:6',
            'UHN' => 'exists:Houses,UHN',
            'UPN' => 'exists:Property,UPN',
            'SBPNumber' => 'exists:Permits,SBPNumber'
        );

        //create or update customer profile
        function createOrUpdate() {
          function customize($id){
            $name = Input::get('FirstName').' '.Input::get('MiddleName').' '.Input::get('LastName');
            $record = [
              'CustomerName' => $name, 'ContactPerson' => $name, 'IDNO' => Input::get('IDNumber'),
              'Mobile1' => Input::get('Mobile'), 'Email' => Input::get('Email')
            ];
            Customer::where('CustomerSupplierID',$id)->update($record);
            $pid = Customer::where('CustomerSupplierID',$id)->pluck('CustomerProfileID');
            return $pid;
          }

          if(Input::get('UHN')) {
            //update house tenant in customers table
            $csid = DB::table('HouseTenancy')->where('UHN',Input::get('UHN'))->pluck('CustomerSupplierID');
            return customize($csid);
          } elseif(Input::get('UPN')) {
            //update land tenant in customers table
            $csid = DB::table('Property')->where('UPN', Input::get('UPN'))->pluck('CustomerSupplierID');
            return customize($csid);
          } elseif(Input::get('SBPNumber')) {
            //update licensed business in customers table
            $csid = DB::table('Permits')->where('SBPNumber', Input::get('SBPNumber'))->pluck('CustomerSupplierID');
            return customize($csid);
          } else {
            //create new customer in customers table
            $params = ['CreatedBy'=>0,'status'=>1];

            $cust = new Customer();
            $cust->CreatedBy = 1;
            $cust->CustomerTypeID = 1;
            $cust->CustomerName = Input::get('FirstName');
            $cust->CustomerProfileID = Api::CustomerProfileID($params); #create profile

            $cust->save();
            return $cust->CustomerProfileID;
          }
        }

        $v = Validator::make(Input::all(),$rules);

        if ($v->passes()){
            $data['confirm_token'] = md5(uniqid(mt_rand(), true));
            $data['email']=Input::get('email');
            $input = Input::all();
            $params = ['CreatedBy'=>0,'status'=>1];
            #$id = Api::CustomerProfileID($params);
            #dd($id);

            //create or update customer account before creating user account
            $id = createOrUpdate();


            $creds = array(
                'FirstName' => $input['FirstName'],
                'MiddleName' =>$input['MiddleName'],
                'LastName' =>$input['LastName'],
                'IDNumber' => $input['IDNumber'],
                'Mobile'=>$input['Mobile'],
                'Email' => $input['email'],
                'CustomerProfileID'=>$id,
                'password' => $input['password'],
                'ConfirmationToken' => $data['confirm_token'],
                'Active'=>0,
                'ChangePassword'=>0
            );
            if ($this->register($creds)){
                $me = Api::FindUserBy('Email',$creds['Email']);
                $pro = CustomerProfile::findOrFail($id);
                $pro->CreatedBy = $me->id();
                $pro->save();

                $data['FirstName'] = $creds['FirstName'];
                $data['LastName'] = $creds['LastName'];
                $data['MiddleName'] = $creds['MiddleName'];
                $data['password'] = $creds['password'];
                $data['EmailTitle'] = 'New Account Information';
                $data['subject'] = $data['EmailTitle'];
                $data['email'] = $creds['Email'];

                //Api::sendMail('ActivateAccount',$data);

                Session::flash('success_msg','User registration success. Please activate your account user the confirmation
                    link sent to your email address');
                return Redirect::route('home');
            }else{
                Session::flash('error_msg','Registration failure contact Support Team');
                return Redirect::route('register')
                    ->withInput(Input::except(array('password','password_confirmation')));
            }
        }
        return Redirect::route('get.register')
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
            'FirstName' => 'required|max:255',
            'MiddleName' => 'required|max:255',
            'LastName' => 'required|max:255',
            'Mobile' => 'required|max:15',
            'IDNumber' => 'required|max:12',
            'email' => 'required|email|max:255|unique:UserProfile',
            'password' => 'required|min:6',
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

        return User::create([
            'FirstName' => $creds['FirstName'],
            'MiddleName' =>$creds['MiddleName'],
            'LastName' =>$creds['LastName'],
            'IDNumber' => $creds['IDNumber'],
            'Mobile'=>$creds['Mobile'],
            'Email' => $creds['Email'],
            'CustomerProfileID'=>$creds['CustomerProfileID'],
            'password' => Hash::make($creds['password']),
            'ConfirmationToken'=>$creds['ConfirmationToken'],
            'Active'=>$creds['Active'],
            'ChangePassword'=>$creds['ChangePassword']
        ]);

    }

    /**
     * @param $userId
     * @param $code
     * @return mixed
     * Activate user
     */
    public function activate($code){
        $user = Api::FindUserBy('ConfirmationToken',$code);
        //print_r($user);die();
        if ($user){
            if ($user->Active == 1){
                Session::flash('error_msg','Your account is already confirmed');
                return Redirect::to('login');
            }else{
                if ($user->ConfirmationToken === $code){
                    $user->ConfirmationToken = '';
                    $user->Active =1;
                    $user->save();

                    Session::flash('success_msg', 'Account Activation Successful Please login below.');
                    return Redirect::to('/login');
                }
            }
        }

        Session::flash('error_msg', 'Unable to activate user Try again later or contact Support Team.');
        return Redirect::to('/login');
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

    public function getChangePassword()
    {
        Session::put('change_password_token',csrf_token());
        #dd(['user__'=>Session::get('user__'),'change_password_token'=>Session::get('change_password_token')]);

        return View::make('auth.reset',['user__'=>Session::get('user__'),'change_password_token'=>Session::get('change_password_token')]);
    }

    public function changePassword()
    {
        $token = Session::get('change_password_token');
        $rules = array(
            'user__'=>'required|exists:UserProfile,Email',
            'change_password_token'=>"required|in:$token",
            'password'=>'required|confirmed|min:6',
        );
        $messages = array(
            'in' => 'Invalid request token',
        );


        $valid = Validator::make(Input::all(),$rules,$messages);
        if ($valid->passes()){
            //var_dump(Input::all());die();
            $user = Api::FindUserBy('Email',Input::get('user__'));
            $user->password = Hash::make(Input::get('password'));
            $user->ChangePassword = '';
            $user->save();

            Session::flash('success_msg','You have successfully changed your password');
            return Redirect::route('my.profile');
        }
        //var_dump($valid->errors());die();
        return Redirect::action('UsersController@getChangePassword')
            ->withErrors($valid);
    }

    public function getUsersList(){
        $profileID = Auth::user()->CustomerProfileID;

        $users = User::where('CustomerProfileID','=',$profileID)->get();

        return View::make('users.index',['entities'=>$users]);
    }


    public function showMyProfile()
    {
        $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
        $user = Agent::findOrFail(Auth::id());
        return View::make('auth.profile',['entity'=>$user],['bill' => $bill]);
    }

    public function userProfile()   {
        $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
        $user = Agent::findOrFail(Auth::id());
        return View::make('auth.userprofile', [ 'user' => $user, 'bill' => $bill ]);
    }

    public function userPassword()   {
        $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
        $user = Agent::findOrFail(Auth::id());
        return View::make('auth.userpassword', [ 'user' => $user, 'bill' => $bill ]);
    }

    public function updateUserProfile()   {
        $user = Auth::user();
        $data = (array) Input::all();
        $user->fill($data);
        $user->save();

        Session::flash('message', 'Your account information has been updated');
        return Redirect::back();

    }

    public function updateUserPassword()   {
        $user = Auth::user();
        $data = (array) Input::all();

        $rules = array( 'current' => 'required' , 'password' => 'required|confirmed');
        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
          Session::flash('message', 'Your input has problems');
          return Redirect::back()->withErrors($validator);
        }

        if(Hash::check($data['current'], $user->password)){
          $user->password = Hash::make($data['password']);
          $user->save();

          Session::flash('message', 'Your account information has been updated');
          return Redirect::back();
        }

        Session::flash('message', 'The Current Password is wrong!');
        return Redirect::back();

    }

    public function businessProfile($cid)    {
        $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
        $customer = Customer::where('CustomerID', intval($cid))->first()->toArray();
        $form = ServiceForm::findOrFail(1);
        $fields = [
          'PIN' => 4182,
          'Ward' => 4186,
          'Email' => 4180,
          'Website' => 4181,
          'SubCounty' => 4185,
          'PostalCode' => 4178,
          'Mobile1' => 12240,
          'Telephone1' => 4179,
          'County' => 15379,
          'CustomerName' => 4176,
          'PostalAddress' => 4177,
          'BusinessID' => 4184,
          'PlotNo' => 12238,
          'PhysicalAddress' => 12256,
          'BusinessRegistrationNumber' => 12247,
          'BusinessTypeID'=>13283
        ];

        //Get Business Attachements
        $Countries = Countries::select(['Id', 'Name'])->get();

        $BusinessAttacheMents = DB::table('BusinessAttacheMents as BA')
                                ->where('BA.BusinessNo', Session::get('customer')->CustomerID)
                                ->join('BusinessRegistrationDocumentTypes as BRT', 'BRT.DocTypeID', '=', 'BA.DocTypeID')
                                ->get();

         
        $Directors = Directors::with('Attachements')
        ->where('CompanyID','=',Session::get('customer')->CustomerID)
        ->where('Deleted',0)
        ->get();
     
        //Get Attachemenets
        $DirectorAttachements = false;
        if($Directors){
            foreach($Directors as $Director){
                if(isset($Director->Attachements)){
                    if($Director->Attachements){
                        $DirectorAttachements = $Director->Attachements;
                    }
                }
             
            }
        }
     
       

        $CompanyDirectors  = Directors::select(['DirectorsID','FirstName', 
        'LastName', 'KRAPIN', 'IDNO', 'created_at'])
            ->where('CompanyID',intval(Session::get('customer')->CustomerID))
            ->where('Deleted',0)
            ->get();
        
        // echo '<pre>';
        // print_r($CompanyDirectors );
        // exit;


        $data = $d = [];
        foreach ($fields as $key => $value) {
          if(array_key_exists($key, $customer)) { array_push($data, [ strval($value) => $customer[$key] ]); }
          else { array_push($data, [ strval($value) => null ]); }
        }
        foreach($data as $k=>$v) { $i = key($v);  $d[$i] = $v[$i]; }
        //dd($d);
        return View::make('auth.businessprofile', ['bill'=>$bill,
            'form'=> $form, 
            'application' => $d,
            'BusinessAttacheMents'=>$BusinessAttacheMents,
            'Directors'=>$CompanyDirectors,
            'Countries'=>$Countries,
            'DirectorAttachements'=>$DirectorAttachements
          ]);
    }
    /**
     * Show user profile as per user
     * @param $id
     * @return mixed
     */
    public function showUserProfile($id)
    {
        $user = User::findOrFail($id);
        return View::make('auth.profile',['entity'=>$user]);
    }
    public function updatebusinessProfile($cid){
        $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
        $customer = Customer::where('CustomerID', intval($cid))->first()->toArray();
        $form = ServiceForm::findOrFail(1);
        $fields = [
          'PIN' => 4182,
          'Ward' => 4186,
          'Email' => 4180,
          'Website' => 4181,
          'SubCounty' => 4185,
          'PostalCode' => 4178,
          'Mobile1' => 12240,
          'Telephone1' => 4179,
          'County' => 15379,
          'CustomerName' => 4176,
          'PostalAddress' => 4177,
          'BusinessID' => 4184,
          'PlotNo' => 12238,
          'PhysicalAddress' => 12256,
          'BusinessRegistrationNumber' => 12247,
          'BusinessTypeID'=>13283
        ];

        //Get Business Attachements


        $BusinessAttacheMents = DB::table('BusinessAttacheMents as BA')
                                ->where('BA.BusinessNo', $cid)
                                ->join('BusinessRegistrationDocumentTypes as BRT', 'BRT.DocTypeID', '=', 'BA.DocTypeID')
                                ->get();
                                // echo '<pre>';
                                // print_r($BusinessAttacheMents);
                                // exit;

         $data = $d = [];
        foreach ($fields as $key => $value) {
          if(array_key_exists($key, $customer)) { array_push($data, [ strval($value) => $customer[$key] ]); }
          else { array_push($data, [ strval($value) => null ]); }
        }

        foreach($data as $k=>$v) { $i = key($v);  $d[$i] = $v[$i]; }
        return View::make('auth.updatebusinessprofile', ['bill'=>$bill,
        'form'=> $form, 
        'application' => $d,
        'BusinessAttacheMents'=>$BusinessAttacheMents,
        ]);

    }

}
