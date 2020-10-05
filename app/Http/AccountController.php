<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Department as Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\BusinessProfile;

class AccountController extends Controller {

    public function __construct() {
      $this->middleware('auth');
      $this->currentUser = \Auth::user();
      $this->loadAndAuthorizeResource();
    }

    # list accounts
    public function index() {
      dd($this->accounts);
        //return view('departments.index')->with('departments', $this->departments);
    }

    # show the form for creating account
    public function create() {
      return view('accounts.create');
    }

    #  Show the form for editing the specified resource. Embeds account object.
    public function edit($id)
    {
      if (is_null($this->account)) {
        return redirect('accounts');
      }
      return view('accounts.edit', compact_property($this, 'account'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
      dd($this);
      $this->account->fill($this->params['account']);

      if ($this->account->save()) {

        $profile = BusinessProfile::create([
          'code' => $this->params['account']['code'],
          'phone' => $this->params['account']['phone'],
          'email' => $this->params['account']['email'],
          'website' => $this->params['account']['website'],
          'address' => $this->params['account']['address'],
          'service_id' => $this->params['account']['service_id'],
          'department_id' => $this->params['account']['department_id']
        ]);

        $this->account->businessProfile()->save($profile);
        $this->account->users()->sync([$this->currentUser->id], false);

        return redirect()->back()->with('message', 'account Added!');

        } else {
          dd('error!');
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
      $this->account->fill($this->params['account']);
      if ($this->account->save()) {
        return redirect()->back()->with('message', 'account Added!');
        } else {
          dd('error!');
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
