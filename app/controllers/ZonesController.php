<?php

class ZonesController extends Controller {
  public function getList() {
    /*dd('got here');*/
    $data = DB::table('businesszones as b')
      ->join('Wards as w', 'w.wardid', '=', 'b.wardid')
      ->join('Subcounty as s', 's.subcountyid', '=', 'w.subcountyid')
      ->get(['b.ZoneName', 'w.Wardname', 's.subcountyname']);
      #->get();
    //dd($data);
    return Response::json($data);
  }
}
