<?php

class Api {
    /**
     * @param array $params
     * @return mixed|static
     */
    public static function CustomerProfileID($params = array())
    {
        if (Auth::id()){
            return Auth::user()->CustomerProfileID;
        } else {
            $status = CustomerProfileStatus::findOrFail(1);
            $profile = new CustomerProfile();
            $profile->CreatedBy = $params['CreatedBy'];
            $profile->CustomerProfileStatusID = $status->id();

            $profile->save();
            #dd($profile);

            return $profile->id();
        }


    }

    /**
     *
     * Find user by key
     * @param $key
     * @param $value
     * @return bool
     */

    public static function FindUserBy($key,$value)
    {
        $user = User::where($key ,'=',$value)->first();

        if ($user){
            return $user;
        }

        return false;
    }

    /**
     *
     * Find user by key
     * @param $key
     * @param $value
     * @return bool
     */

    public static function FindAgentBy($key,$value) {
      $agent = Agent::where($key ,'=', $value)->first();

      if ($agent){
          return $agent;
      }

      return false;
    }

    /**
     * upload given file to specific destination
     * @param $file
     * @param array $params
     * @return bool|string
     */

    public static function upload($file,$params = array())
    {
        if(isset($file)){
          $extension = $file->getClientOriginalExtension();
          $name = Auth::id().'-'.Api::clean($params['name']).'.'.$extension;
          if ($file->move(public_path().$params['path'], $name)){
              return $params['path'].'/'.$name;
          }

          return false;
        }
    }

    /**
     * Remove unwanted characters from string
     * @param $string
     * @return mixed
     */
    public static function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }

    /**
     * Send mail
     * @param $template
     * @param $data
     */
    public static function sendMail($template,$data)
    {
        Mail::queue('emails.'.$template, $data, function($m) use ($data) {
            $m->to($data['email'])
                ->subject($data['subject']);
        });
    }

    /**
     * Diplay status label
     * @param $var
     * @param $true
     * @param $false
     * @return string
     */
    public static function status($var,$true,$false){
        if ($var){
            return '<span class="label label-sm label-success">'.$true.'</span>';

        }

        return '<span class="label label-sm label-warning">'.$false.'</span>';
    }

    public static function RandomBackgroundColor(){
        $colors = [
            'lime','green','emerald','teal','cyan','cobalt','indigo','violet',
            'pink','magenta','crimson','red','orange','amber','yellow','brown','olive','steel',
            'mauve','taupe','gray','dark','darker','darkBrown','darkCrimson','darkMagenta',
            'darkIndigo','darkCyan','darkCobalt','darkTeal','darkEmerald','darkGreen','darkOrange','darkRed','darkPink','darkViolet',
            'darkBlue','lightBlue','lightTeal','lightOlive','lightOrange','lightPink','lightRed','lightGreen'
        ];
        $max = count($colors);

        $index =  rand(0,$max -1);

        return $colors[$index];
    }

    public static function FormFieldTemplate($label,$columnWidth,$fieldClass,$field){
        echo  "
            <div class='form-group'>
                <label class='label'>$label</label>
                <div class='input-control $fieldClass'>
                    $field
                </div>
            </div>
        ";
    }

    public static function FieldTemplate($label,$columnWidth,$fieldClass,$field, $required)
    {
      if($required) {
        echo  "
            <div class='required field'>
                <label>$label</label>
                $field
            </div>
        ";
      } else {
        echo  "
            <div class='field'>
                <label>$label</label>
                $field
            </div>
        ";
      }

    }

    public static function ResultArray($q)
    {
        $query = str_replace("#ID","0" ,$q);
        $result = DB::select($query);
        $vars = (array)$result;
        $values[] = [0 => 'select'];
        foreach ($vars as $var){
            //convert object class to array;
            $object = (array)$var;

            //get an array list of the values
            $list = array_values($object);

            //create an array index of values
            $values[] = [$list[0]=>$list[1]];
        }
        //$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($values));
        $vals = [];
        if($values) {
          foreach($values as $v) {
            //array_push($vals, $v);
            foreach($v as $key => $val) {
              $vals[$key] = $val;
            }
          }
        }

        return ($vals);
    }
    public  static function CreateFormField($columnId)
    {
        $col = FormColumns::find($columnId);
        $dataType = $col->dataType;

        switch ($dataType){
            case "Text":
                $field = Form::text("ColumnID[".$col->id()."]",Input::old($col->id()),['class'=>'form-control', 'id' => $col->id()]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'text';
                break;
            case "Number":
                $field = Form::number("ColumnID[".$col->id()."]",Input::old($col->id()),['class'=>'form-control', 'id' => $col->id()]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'number';
                break;
            case 'Option':
                $values = Api::ResultArray($col->Notes);
                $field = Form::select("ColumnID[".$col->id()."]",$values,Input::old($col->id()),['class'=>'form-control', 'id' => $col->id()]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'select';
                break;
            case 'OptionCommaSeparated':
                $values = explode(',',$col->Notes);
                $field = Form::select("ColumnID[".$col->id()."]",$values,Input::old($col->id()),['class'=>'form-control', 'id' => $col->id()]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'select';
                break;
            default:
                $field = Form::text("ColumnID[".$col->id()."]",Input::old($col->id()),['class'=>'form-control', 'id' => $col->id()]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'text';
                break;
        }

        return Api::FormFieldTemplate($label,$width,$class,$field);
    }



    public  static function CustomFormField($columnId, $defaultValue = '',$disabled='')
    {

        $col = FormColumns::find($columnId);
        // echo '<pre>';
        // print_r( $co);
        // exit;

        $dataType = $col->dataType;
        $default = Input::old($col->id()) || $defaultValue;
        

        switch ($dataType){
            case "Text":
                $field = Form::text("ColumnID[".$col->id()."]", Input::old($col->id()) ,['class'=>'ui', 'id' => $col->id(), $disabled]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'text';
                break;
            case "File":
                $field = Form::file("ColumnID[".$col->id()."]");
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'text';
                break;
            case "Textarea":
                $field = Form::textarea("ColumnID[".$col->id()."]",Input::old($col->id()),['class'=>'ui', 'id' => $col->id()]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'text';
                break;
            case "Number":
                $field = Form::number("ColumnID[".$col->id()."]",Input::old($col->id()),['class'=>'form-control', 'id' => $col->id()]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'number';
                break;
            case 'Option':
                $listener = '';
                if (!$col->FilterColumnID == null && !$col->FilterColumnID == 0) {
                  //$listener = 'filterSelect(this.value,'.$col->FilterColumnID.')';
                }
                //if ($col->id() == 11203) { dd($listener); }
                $values = Api::ResultArray($col->Notes);
                $field = Form::select("ColumnID[".$col->id()."]",$values,Input::old($col->id()),['class'=>'ui dropdown', 'id' => $col->id(), 'onChange' => $listener ]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'select';
                break;
            case 'OptionCommaSeparated':
                $values = explode(',',$col->Notes);
                $field = Form::select("ColumnID[".$col->id()."]",$values,Input::old($col->id()),['class'=>'ui dropdown', 'id' => $col->id()]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'select';
                break;
            default:
                $field = Form::text("ColumnID[".$col->id()."]",Input::old($col->id()),['class'=>'form-control', 'id' => $col->id()]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'text';
                break;
        }

        if($col->Mandatory == 1) {
          $required = true;
        } else {
          $required = false;
        }

        return Api::FieldTemplate($label,$width,$class,$field, $required);
    }

    public  static function RenewalCustomFormField($columnId, $defaultValue = '',$disabled='')
    {

        $col = LicenceRenewalFormColumns::find($columnId);
        // echo '<pre>';
        // print_r( $co);
        // exit;

        $dataType = $col->dataType;
        $default = Input::old($col->id()) || $defaultValue;
        

        switch ($dataType){
            case "Text":
                $field = Form::text("ColumnID[".$col->id()."]", Input::old($col->id()) ,['class'=>'ui', 'id' => $col->id(), $disabled]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'text';
                break;
            case "File":
                $field = Form::file("ColumnID[".$col->id()."]");
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'text';
                break;
            case "Textarea":
                $field = Form::textarea("ColumnID[".$col->id()."]",Input::old($col->id()),['class'=>'ui', 'id' => $col->id()]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'text';
                break;
            case "Number":
                $field = Form::number("ColumnID[".$col->id()."]",Input::old($col->id()),['class'=>'form-control', 'id' => $col->id()]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'number';
                break;
            case 'Option':
                $listener = '';
                if (!$col->FilterColumnID == null && !$col->FilterColumnID == 0) {
                  //$listener = 'filterSelect(this.value,'.$col->FilterColumnID.')';
                }
                //if ($col->id() == 11203) { dd($listener); }
                $values = Api::ResultArray($col->Notes);
                $field = Form::select("ColumnID[".$col->id()."]",$values,Input::old($col->id()),['class'=>'ui dropdown', 'id' => $col->id(), 'onChange' => $listener ]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'select';
                break;
            case 'OptionCommaSeparated':
                $values = explode(',',$col->Notes);
                $field = Form::select("ColumnID[".$col->id()."]",$values,Input::old($col->id()),['class'=>'ui dropdown', 'id' => $col->id()]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'select';
                break;
            default:
                $field = Form::text("ColumnID[".$col->id()."]",Input::old($col->id()),['class'=>'form-control', 'id' => $col->id()]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'text';
                break;
        }

        if($col->Mandatory == 1) {
          $required = true;
        } else {
          $required = false;
        }

        return Api::FieldTemplate($label,$width,$class,$field, $required);
    }

    public  static function CustomFormInput($columnId, $input,$disabled='')
    {
        
        $col = FormColumns::findOrFail($columnId);
        
        

        //if(!$col) { return; }
        $dataType = $col->dataType;

        switch ($dataType){
            case "Text":
                $field = Form::text("ColumnID[".$col->id()."]", $input ,['class'=>'ui', 'id' => $col->id(), $disabled]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'text';
                break;
            case "Textarea":
                $field = Form::textarea("ColumnID[".$col->id()."]", $input ,['class'=>'ui', 'id' => $col->id()]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'text';
                break;
            case "Number":
                $field = Form::number("ColumnID[".$col->id()."]", $input ,['class'=>'form-control', 'id' => $col->id()]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'number';
                break;
            case 'Option':
                $listener = '';
                if (!$col->FilterColumnID == null && !$col->FilterColumnID == 0) {
                    $listener = 'filterSelect(this.value,'.$col->FilterColumnID.')';
                }
                $values = Api::ResultArray($col->Notes);
                $field = Form::select("ColumnID[".$col->id()."]",$values, $input ,['class'=>'ui dropdown', 'id' => $col->id(), 'onChange' => $listener ]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'select';
                break;
            case 'OptionCommaSeparated':
                $values = explode(',',$col->Notes);
                $field = Form::select("ColumnID[".$col->id()."]",$values, $input ,['class'=>'ui dropdown', 'id' => $col->id()]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'select';
                break;
            default:
                $field = Form::text("ColumnID[".$col->id()."]", $input ,['class'=>'form-control', 'id' => $col->id()]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'text';
                break;
        }

        if($col->Mandatory == 1) {
          $required = true;
        } else {
          $required = false;
        }

        return Api::FieldTemplate($label,$width,$class,$field, $required);
    }

    public  static function CustomFormInputReadOnly($columnId, $input,$disabled='')
    {
        
        $col = FormColumns::findOrFail($columnId);
        
        

        //if(!$col) { return; }
        $dataType = $col->dataType;

        switch ($dataType){
            case "Text":
                $field = Form::text("ColumnID[".$col->id()."]", $input ,['class'=>'ui', 'id' => $col->id(), 'readonly' => true]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'text';
                break;
            case "Textarea":
                $field = Form::textarea("ColumnID[".$col->id()."]", $input ,['class'=>'ui', 'id' => $col->id(), 'readonly' => true]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'text';
                break;
            case "Number":
                $field = Form::number("ColumnID[".$col->id()."]", $input ,['class'=>'form-control', 'id' => $col->id(), 'readonly' => true]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'number';
                break;
            case 'Option':
                $listener = '';
                if (!$col->FilterColumnID == null && !$col->FilterColumnID == 0) {
                    $listener = 'filterSelect(this.value,'.$col->FilterColumnID.')';
                }
                $values = Api::ResultArray($col->Notes);
                $field = Form::select("ColumnID[".$col->id()."]",$values, $input ,['class'=>'ui dropdown', 'id' => $col->id(), 'onChange' => $listener, 'disabled' => true ]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'select';
                break;
            case 'OptionCommaSeparated':
                $values = explode(',',$col->Notes);
                $field = Form::select("ColumnID[".$col->id()."]",$values, $input ,['class'=>'ui dropdown', 'id' => $col->id(), 'disabled' => true]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'select';
                break;
            default:
                $field = Form::text("ColumnID[".$col->id()."]", $input ,['class'=>'form-control', 'id' => $col->id(), 'readonly' => true]);
                $label = $col;
                $width = $col->ColumnSize;
                $class = 'text';
                break;
        }

        if($col->Mandatory == 1) {
          $required = true;
        } else {
          $required = false;
        }

        return Api::FieldTemplate($label,$width,$class,$field, $required);
    }

    public static function AddFormData($params= array())   {
        $data = new FormData();
        $data->FormColumnID = $params['ColumnID'];
        $data->ServiceHeaderID = $params['ServiceHeaderID'];
        $data->Value = $params['Value'];
        $data->CreatedDate = date('Y-m-d H:i:s');
        $data->CreatedBy = Auth::id();
        $data->save();

        if ($data->FormDataID){ 
            return true; 
        }

        return false;
    }

    public static function AddLicenceRenewalFormData($params= array())   {
        $data = new LicenceRenewalFormData();
        $data->FormColumnID = $params['ColumnID'];
        $data->LicenceId = $params['LicenceId'];
        $data->Value = $params['Value'];
        $data->CreatedDate = date('Y-m-d H:i:s');
        $data->CreatedBy = Auth::id();
        $data->save();

        if ($data->LiceneceRenewalFormDataId){ 
            return true; 
        }

        return false;
    }

    public static function UpdateFormData($params= array())   {
        $data = FormData::where('FormColumnID', $params['ColumnID'])
          ->where('ServiceHeaderID', $params['ServiceHeaderID'])
          ->first();

        //if(empty($data)) { $data = new FormData(); }

        $data->FormColumnID = $params['ColumnID'];
        $data->ServiceHeaderID = $params['ServiceHeaderID'];
        $data->Value = $params['Value'];
        $data->CreatedDate = date('Y-m-d H:i:s');
        $data->CreatedBy = Auth::id();
        $data->save();

        if ($data->FormDataID){ return true; }

        return false;
    }

    public static function showLogo($class=null,$height=100,$width=100)
    {
        return '<img class="'.$class.'" src="'.asset('images/ug-logo.png').'" height="'.$height.'" width="'.$width.'" />';
    }

    public static function stampPaid($class=null,$height = 100,$width=100)
    {
        return '<img class="'.$class.'" src="'.asset('images/stamp-paid.png').'" height="'.$height.'" width="'.$width.'" />';
    }

}
