<?php

class SbpController extends BaseController {
  public function requestService() {

    $formId = 2;
    $serviceId = 298;
    $form = ServiceForm::findOrFail(intval($formId));

    return View::make('sbp.request', [ 'form' => $form ]);
  }
}
