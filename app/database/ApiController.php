<?php

class ApiController extends Controller {

  public function departments() {
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
    //dd($bill->toArray());
    return Response::json($bill->toArray());
  }

}
