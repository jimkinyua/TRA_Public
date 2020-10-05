<!DOCTYPE html>
<html lang="en">
  <head>
  	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  	<title>County Revenue</title>

    <link href="{{asset('css/layout.css')}}" rel="stylesheet">
    <link href="{{asset('css/dropper/datedropper.css')}}" rel="stylesheet">
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/1.11.6/semantic.min.css">

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  	<script src="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/1.11.6/semantic.min.js"></script>

  	<script src="{{asset('js/datedropper.min.js')}}"></script>
    
    <script type="text/javascript" src="https://raw.githubusercontent.com/defunkt/jquery-pjax/master/jquery.pjax.js"></script>

    <script type="text/javascript">
      var zones = [{"id":"1","name":"Ainabkoi","wards":[{"id":"1","name":"Kapsoya"},
      {"id":"2","name":"Kaptagat"},{"id":"3","name":"Ainabkoi-Olare"},{"id":"29","name":"Test Ward"}]},
      {"id":"2","name":"Kapseret","wards":[{"id":"4","name":"Kipkenyo"},{"id":"5","name":"Langas"},
      {"id":"6","name":"Simat-kapsaret"},{"id":"7","name":"Ngeria"},{"id":"8","name":"Megun"}]},
      {"id":"3","name":"Kesses","wards":[{"id":"9","name":"Race Course"},{"id":"10","name":"Tarakwa"},
      {"id":"11","name":"Tulwel-Chuiyat"},{"id":"12","name":"Cheptiret-Kipchamo"}]},
      {"id":"4","name":"Moiben","wards":[{"id":"14","name":"Kimumu"},{"id":"15","name":"Karuna-Moibeki"},
      {"id":"16","name":"Moiben"},{"id":"17","name":"Sergoit"},{"id":"18","name":"Kapkures"}]},
      {"id":"5","name":"Soy","wards":[{"id":"19","name":"Kuinet-Kapsuswa"},{"id":"20","name":"Kiplombe"},
      {"id":"21","name":"Kipsomba"},{"id":"22","name":"Soy"},{"id":"23","name":"Ziwa"},{"id":"30","name":"Segero"},
      {"id":"31","name":"Mois Bridge"}]},{"id":"6","name":"Turbo","wards":[{"id":"24","name":"Kamagut"},
      {"id":"25","name":"Huruma"},{"id":"26","name":"Ngenyilel"},{"id":"27","name":"Kapsaos"},{"id":"28","name":"Tapsagoi"}]}];
      var filterSelect = function(id, target){
        if(id == 0) { return; }
        console.log(id, target);

        if(id == undefined) {  return; }

        var subcounty = zones.filter(function(it) {
          return it.id == id;
        })[0];

        console.log('undef', subcounty);

        var toAppend = '';
        subcounty.wards.forEach(function (it) {
          toAppend += '<option value="'+it.d+'" selected>'+it.name+'</option>';
        });

        var data = { code: 200, options: subcounty.wards };
        console.log('data', data);

        //$.post('{{route('filter.select')}}',{FilterColumnID: target, SelectedID: id },function(data){
          var targetEl = "[name='ColumnID[" + target + "]']";

            //console.log(data);
          if (data.code == 200){
            $.each(data.options,function(id,name){
              //toAppend += '<option value="'+id+'" selected>'+name+'</option>';
            });
          }
          $(targetEl).html(toAppend);
          $(targetEl).parent().dropdown('set text', 'select');
        //});
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

      });
    </script>

  </head>

  <body id="layout">

    <div id="landing" class="ui grid">

      <header id="header" class="row">
        <h3 id="heading" class="ui center aligned header">
          <img id="logo-img" src="{{asset('images/logo.png')}}" class="ui image">
          <div class="content">
            TOURISM REGULATORY AUTHORITY SELF-SERVICE PORTAL
            <div class="sub header">
              <a target='_blank' href="http://attain-es.com/">
                website
                <i class="external icon"></i>
              </a>
            </div>
          </div>
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
                          <div class="description"> info@attain-es.com</div>
                        </div>
                      </div>
                      <div class="item">
                        <i class="orange mail icon"></i>
                        <div class="content">
                          <a class="header">Postal Address</a>
                          <div class="description">P.O Box 40 - 30100, NAIROBI</div>
                        </div>
                      </div>
                      <div class="item">
                        <i class="orange phone square icon"></i>
                        <div class="content">
                          <a class="header">Phone Number</a>
                          <div class="description">053-2016000 || 020-32603</div>
                        </div>
                      </div>
                      <div class="item">
                        <i class="orange fax icon"></i>
                        <div class="content">
                          <a class="header">Fax</a>
                          <div class="description">+254-053-2062884</div>
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
                          <a class="header">County Mission</a>
                        </div>
                      </div>
                      <div class="item">
                        <i class="circular orange angle double right icon"></i>
                        <div class="content">
                          <a class="header">County Vision</a>
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
