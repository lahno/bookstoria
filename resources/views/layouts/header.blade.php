<header>
    <a class="uparrow" href="#go_top"><i class="fas fa-arrow-circle-up"></i></a>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" id="go_top">
        <a class="navbar-brand logo" href="/"><img src="/img/logo.png" width="auto" height="60"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">

                <li class="nav-item dropdown dropdown-auto" id="goup">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Жанры
                    </a>
                    <div class="dropdown-menu dropdown-menu-auto dropdown-col show" aria-labelledby="navbarDropdown" style="display: none; width: 300px;">
                        <ul>
                            <a class="dropdown-item" href="/allbook">Все жанры</a>
                            @foreach($cats as $c)
                                <a class="dropdown-item" href="/category/{{$c->id}}">{{$c->name}}</a>
                            @endforeach
                            <a class="dropdown-item" href="#"  style="background-color: green; color: white;">Мировые бестселлеры<br>(переводы онлайн)</a>
                        </ul>
                    </div>
                </li>

                <li class="nav-item dropdown dropdown-auto">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Тематические подборки
                    </a>
                    <div class="dropdown-menu dropdown-menu-auto dropdown-col show" aria-labelledby="navbarDropdown2" style="display: none;">
                        <ul class="col-12 col-md-6 col-menu-test">
                            @php
                                $count = $cols->count();
                                $dec = $count/2;
                            @endphp
                            @foreach($cols as $key=>$col)

                                @if($key <= $dec)
                                 <a class="dropdown-item" href="/collections/{{$col->id}}">{{$col->name}}</a>
                                @endif
                            @endforeach
                        </ul>
                        <ul class="col-12 col-md-6 col-menu-test">
                            @foreach($cols as $key=>$col)
                                @if($key > $dec)
                                    <a class="dropdown-item" href="/collections/{{$col->id}}">{{$col->name}}</a>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://www.youtube.com/channel/UCR-ZYQtlunzYMcGh0Jwxo_g" target="_blank">Книги на YouTube</a>
                </li>

               <li class="nav-item">
                    <a class="nav-link" href="/blogs">Блоги</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/competition/all">Конкурсы</a>
                </li>
                {{--@guest
                    <li class="nav-item visible_mobile">
                        <a class="nav-link" href="{{ route('login') }}">Войти</a>
                    </li>
                    <li class="nav-item visible_mobile">
                        <a class="nav-link" href="{{ route('register') }}">Зарегестрироваться</a>
                    </li>
                @endguest--}}
            </ul>
            <!-- Sign up -->
            <div class="dropdown visible_mobile">
                <button class="btn btn-secondary dropdown-toggle menu-icon" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user"></i>
                </button>
                <div class="dropdown-menu icon-dropdown dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                    @guest
                        <a class="dropdown-item" href="{{ route('login') }}">Войти</a>
                        <a class="dropdown-item" href="{{ route('register') }}">Зарегистрироваться</a>
                    @else
                        <a  class="dropdown-item" href="/mybooks" role="button">
                            {{ Auth::user()->name }}
                        </a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                            Выйти
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endguest
                </div>
            </div>
            <form class="form-inline my-2 my-lg-0" type="GET" action="/search">
                @csrf
                <input class="form-control mr-sm-2" type="search" name="search" placeholder="Поиск" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button>
            </form>

        @if(Auth::user())
            <!-- Книги -->
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle menu-icon" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-book"></i>
                </button>
                <ul class="dropdown-menu icon-dropdown dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <li class="dropdown-item"><a href="/mylibrary">
                            Моя библиотека
                        </a>
                    </li>
                    {{--<li class="dropdown-item">Обновления</li>--}}
                </ul>
            </div>
            <!-- Уведомления -->
            {{--<div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle menu-icon" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="far fa-bell"></i>
                </button>
                <ul class="dropdown-menu icon-dropdown dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    @php
                        $notify = \App\Http\Helpers\AdminHelper::getNotify();
                    @endphp
                    @foreach($notify as $n)
                        @if(isset($n->message))
                            <li class="dropdown-item bell-item">{!! $n->message !!}</li>
                        @endif
                    @endforeach
                    <li class="dropdown-item bell-item">Новых оповещений нет</li>
                </ul>
            </div>--}}
        @endif
        <!-- Sign up -->
            <div class="dropdown novisiblemobile">
                <button class="btn btn-secondary dropdown-toggle menu-icon" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user"></i>
                </button>
                <div class="dropdown-menu icon-dropdown dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                    @guest
                        <a class="dropdown-item" href="{{ route('login') }}">Войти</a>
                        <a class="dropdown-item" href="{{ route('register') }}">Зарегистрироваться</a>
                    @else
                        <a  class="dropdown-item" href="/mybooks" role="button">
                            {{ Auth::user()->name }}
                        </a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                            Выйти
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>
</header>