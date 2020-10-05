@extends('portal')

@section('content')
  <div id="landing" class="ui vertically divided grid">

    <header id="header" class="row">
      <div class="three wide column">
        <a href="#" class="ui medium image">
          <img src="{{asset('images/ug-logo.png')}}">
        </a>
      </div>
      <div class="thirteen wide column">
        <div class="middle aligned content">
          <h2 id="heading" class="ui center aligned header">THE COUNTY GOVERNMENT OF UASIN GISHU SELF HELP PORTAL </h2>
        </div>
      </div>
    </header>

    <section class="row">
      <div class="nine wide column">
        @yield('section')
      </div>

      <div class="seven wide column">
        @yield('aside')
      </div>
    </section>

    <footer class="row">
      <div class="sixteen wide column">
        <div class="ui orange piled segment">
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
                        <div class="description"> info@uasingishu.go.ke</div>
                      </div>
                    </div>
                    <div class="item">
                      <i class="orange mail icon"></i>
                      <div class="content">
                        <a class="header">Postal Address</a>
                        <div class="description">P.O Box 40 - 30100, ELDORET</div>
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
@endsection
