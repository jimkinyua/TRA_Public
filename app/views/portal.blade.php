<!DOCTYPE html>
<html lang="en">
  <head>
  	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  	<title>TRA (Public)</title>

    <link href="{{asset('css/layout.css')}}" rel="stylesheet">
    <link href="{{asset('css/dropper/datedropper.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('css/jquery.datepick.css')}}">
  	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/1.11.6/semantic.min.css">
  	<link rel="stylesheet" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css">

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  	<script src="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/1.11.6/semantic.min.js"></script>
    <script type="text/javascript" src="https://rawgit.com/DylanPiercey/set-dom/master/dist/set-dom.js"></script>

    <script src="{{asset('js/JsBarcode.all.min.js')}}"></script>
    <script src="{{asset('js/media.js')}}"></script>
    <script src="{{asset('js/jspdf.min.js')}}"></script>
    <script src="{{asset('js/jspdf.plugin.autotable.js')}}"></script>
    <script src="{{asset('js/datedropper.min.js')}}"></script>
    <script src="//cdn.jsdelivr.net/jquery.metadata/2.0/jquery.metadata.min.js"></script>
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.js"> </script>
    <script type="text/javascript" src="{{asset('js/jquery.plugin.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.datepick.js')}}"></script>
4

    <script type="text/javascript">

      var pdf = function(el) {
        var doc = new jsPDF();
        console.log(doc);
        doc.fromHTML($(el).get(0), 15, 15, {	'width': 170,  });
        document.getElementById("output").src = doc.output('datauristring');
      }

  
        var zones =  $.get('/getcounties/', function(res, status){
          // console.log(res);

        });
          
          // [{"id":"1","name":"Ainabkoi","wards":[{"id":"01","name":"Kapsoya"},
          // {"id":"02","name":"Kaptagat"},{"id":"03","name":"Ainabkoi-Olare"}]},
          // {"id":"2","name":"Kapseret","wards":[{"id":"04","name":"Kipkenyo"},{"id":"05","name":"Langas"},
          // {"id":"06","name":"Simat-kapsaret"},{"id":"07","name":"Ngeria"},{"id":"08","name":"Megun"}]},
          // {"id":"3","name":"Kesses","wards":[{"id":"09","name":"Race Course"},{"id":"10","name":"Tarakwa"},
          // {"id":"11","name":"Tulwel-Chuiyat"},{"id":"12","name":"Cheptiret-Kipchamo"}]},
          // {"id":"4","name":"Moiben","wards":[{"id":"14","name":"Kimumu"},{"id":"15","name":"Karuna-Moibeki"},
          // {"id":"16","name":"Moiben"},{"id":"17","name":"Sergoit"},{"id":"18","name":"Kapkures"}]},
          // {"id":"5","name":"Soy","wards":[{"id":"19","name":"Kuinet-Kapsuswa"},{"id":"20","name":"Kiplombe"},
          // {"id":"21","name":"Kipsomba"},{"id":"22","name":"Soy"},{"id":"23","name":"Ziwa"},{"id":"30","name":"Segero"},
          // {"id":"31","name":"Mois Bridge"}]},{"id":"6","name":"Turbo","wards":[{"id":"24","name":"Kamagut"},
          // {"id":"25","name":"Huruma"},{"id":"26","name":"Ngenyilel"},{"id":"27","name":"Kapsaos"},{"id":"28","name":"Tapsagoi"}]}];

      var filterSelect = function(id, target){
        // console.log('id', id);
        // console.log('target', target);
        var FilterColumnID  = target;

        if(id == 0 || id == undefined) { return; }

        var toAppend = '';
        
        $.get('/filterselect/'+FilterColumnID+'/'+id, function(res, status){
          console.log(res);
          $("#service").empty();
          // var data = (array)res.options;
          
          res.forEach(function (it) {
            console.log(it)
            $('#'+target).append($('<option id="itemId'+ it['SubCountyId'] +'"></option>').attr('value', it['SubCountyName'] ).text(it['SubCountyName'] ));

            // toAppend += '<option value="'+ (it['SubCountyId']).substr(1) + '" selected>'+ it['SubCountyName'] + '</option>';
            //console.log('ward id:', (it.id).substr(1));
          });
          // $.each(res, function(key , value) {
          //   // console.log(value);
          //   toAppend += '<option value="'+value+ '" selected>'+ key + '</option>';
          // });
        });

        // var subcounty = zones.filter(function(it) {
        //   return it.id == id;
        // })[0];

        // if(subcounty == undefined) {

        //    var wards = [];
        //    zones.forEach(function(it) {
        //      wards.push(it.wards);
        //    });
        //    var merged = [].concat.apply([], wards);
        //    var ward = merged.filter(function(it) {
        //      return it.id == id;
        //    })[0];
        //    toAppend += '<option value="'+ward.id+'" selected>'+ward.name+'</option>';

        // } else {

        //   subcounty.wards.forEach(function (it) {
        //     toAppend += '<option value="'+ (it.id).substr(1) + '" selected>'+ it.name + '</option>';
        //     //console.log('ward id:', (it.id).substr(1));
        //   });

        // }

        var targetEl = "[name='ColumnID[" + target + "]']";
        console.log(targetEl);
        $(targetEl).html(toAppend);
        console.log(toAppend);

        $(targetEl).parent().dropdown('set text', 'select');
      }

      $(document).ready(function(){

        $('.ui.dropdown').dropdown();
        $('.ui.accordion').accordion();
        $('.ui.menu')
            .on('click', '.item', function() {
              if(!$(this).hasClass('dropdown')) {
                $(this)
                    .addClass('active')
                    .siblings('.item')
                    .removeClass('active');
              }
            });

        $(".pick_account").click(function(e) {
          window.location.href = $(e.currentTarget).data('url');
        });



      });

      var filterActivity = function() {
        var x = document.getElementById("category").value;
        var el = $("#service");

        if(x == 0) { return; }
        console.log('filtering...');

        var url = "/categoryservices/" + x;
        $.get(url, function(res, status){
          $("#service").empty();
          $.each(res.data, function(i,s) {
            var opt = (s.ServiceCode ? (s.ServiceCode + ' : ') : '') + s.ServiceName ;
            $("#service").append($("<option></option>").attr("value", s.ServiceID).text(opt));
          });
        });
        console.log('Done!');
      }


    </script>

  </head>

  <body id="layout">
    <div id="landing" class="ui grid">

      <header id="header" class="row"  style="height: fit-content">
        <h3 id="heading" class="ui center aligned header">
          <img id="logo-img" src="{{asset('images/logo1.png')}}" class="ui image">
          {{-- <div class="content">
            THE TOURISM REQULATORY AUTHORITY
            <div class="sub header">
              <a target='_blank' href="http://http://www.tra.go.ke/">
                website
                <i class="external icon"></i>
              </a>
            </div>
          </div> --}}
        </h3>
      </header>

      @yield('content')

      <div class="ui section divider"></div>

      <footer class="row">
        <div class="sixteen wide column">
          <div class="ui orange piled attached segment">
            <p>&nbsp</p>
            <footer id="footer">
              <div class="ui three column grid">
                <div class="column">
                  <div class="ui horizontal segment">
                    <div class="ui divided list">
                      <div class="item">
                        <i class="orange conversation icon"></i>
                        <div class="content">
                          <a class="header">Email</a>
                          <div class="description"> customerservice@tra.go.ke</div>
                        </div>
                      </div>
                      <div class="item">
                        <i class="orange mail icon"></i>
                        <div class="content">
                          <a class="header">Postal Address</a>
                          <div class="description">P.O. Box 40241 - 00100 NAIROBI/KENYA</div>
                        </div>
                      </div>
                      <div class="item">
                        <i class="orange phone square icon"></i>
                        <div class="content">
                          <a class="header">Phone Number</a>
                          <div class="description">+254 (20) 2379407 || +254 (20) 2379408</div>
                        </div>
                      </div>
                      <div class="item">
                        <i class="orange fax icon"></i>
                        <div class="content">
                          <a class="header">Fax</a>
                          <div class="description">+254 (20) 2379407-15</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="column">
                  <div class="ui horizontal segment">
                    <div class="ui relaxed divided list">
                      <div class="item">
                        <div class="content">
                          <a class="header">About</a>
                        </div>
                      </div>
                      <div class="item">
                        <i class="circular orange angle double right icon"></i>
                        <div class="content">
                          <a class="header"> Mission</a>
                        </div>
                      </div>
                      <div class="item">
                        <i class="circular orange angle double right icon"></i>
                        <div class="content">
                          <a class="header"> Vision</a>
                        </div>
                      </div>
                      <div class="item">
                        <i class="circular orange angle double right icon"></i>
                        <div class="content">
                          <a class="header">Master Plan</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="column">
                  <div class="ui horizontal segment">
                    <div class="ui relaxed divided list">
                      <div class="item">
                        <div class="content">
                          <a class="header">Social Media</a>
                        </div>
                      </div>
                      <div class="item">
                        <i class="circular inverted blue twitter icon"></i>
                        <div class="content">
                          <a class="header">Twitter</a>
                        </div>
                      </div>
                      <div class="item">
                        <i class="circular facebook icon" style="background: #3b5998; color: #fff;"></i>
                        <div class="content">
                          <a class="header">Facebook</a>
                        </div>
                      </div>
                      <div class="item">
                        <i class="circular inverted red google plus icon"></i>
                        <div class="content">
                          <a class="header">Google Plus</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>


              </div>
            </footer>
            <p>&nbsp</p>
          </div>
        </div>
      </footer>

    </div>

    @yield('script')
  </body>
</html>
