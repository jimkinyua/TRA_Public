<?php

class ApiController extends Controller {

  public function departments() {
    $departments = [];
    $groups = ServiceGroup::select(['ServiceGroupName as GroupName', 'ServiceGroupID as GroupID'])->get();
    foreach ($groups as $group) {
      $cats = Category::where('ServiceGroupID', $group->GroupID)->select(['CategoryName', 'ServiceCategoryID as CategoryID'])->get();
      $group = ['GroupName' => $group->GroupName, 'GroupID' => $group->GroupID, 'GroupCategories' => $cats];
      array_push($departments, $group);
    }
    //$bill->load('categories');
    //dd($departments);
    return Response::json($departments);
  }

}
