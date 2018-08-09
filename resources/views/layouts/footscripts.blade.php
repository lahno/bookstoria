

<!-- Optional JavaScript -->
<script type="text/javascript">
    $(document).on('ready', function() {
        $('.regular').slick({
            infinite: false,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: false
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });
    });

</script>
<script type="text/javascript">
    $(document).on('ready', function() {
        $('.regulartrailer').slick({
            infinite: false,
            speed: 300,
            slidesToShow: 3,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: false
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });
    });

</script>

<script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/wow.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/ajax.js?v=1.2')}}"></script>

@if(Route::current()->getName() === 'readbook' || Route::current()->getName() === 'readchapter')
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
@endif


<script>
    new WOW().init();
    // Инициализация
    // Инициализация при загрузке страницы

</script>
<script>
    jQuery('ul.navbar-nav li.dropdown-auto').hover(function() {
        jQuery(this).find('.dropdown-menu-auto').stop(true, true).delay(1).fadeIn();
    }, function() {
        jQuery(this).find('.dropdown-menu-auto').stop(true, true).delay(100).fadeOut();
    });
</script>




<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>
	$(document).ready(function() {
        $('.js-example-basic-multiple').select2();

        // $('.pagination li a').on('click', function () {
        //     console.log('test');
        //     var li = this.parent('li');
        //     if(li.className != 'disabled' || li.className != 'active' || li.className != 'next' || li.className != 'last'){
        //         document.body.scrollTop = document.documentElement.scrollTop = 0;
        //     }
        // });
    });
</script>

@if(Route::currentRouteName() == 'addchapter' || Route::currentRouteName() == 'editbook')
    <script>
        CKEDITOR.replaceAll();
    </script>
@endif
@if(Route::currentRouteName() == 'home' || Route::currentRouteName() == 'edittbook')
    <script>
        CKEDITOR.replace( 'annotacia' );
    </script>
@endif


{{--Modals--}}
<div class="modal fade" id="modalCreateUserOrder" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title text-center" id="myModalLabel">Для покупки книги введите, пожалуйста, Ваш Email и Пароль</h4>
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="POST" action="{{ route('login') }}" id="formCreateUserOrder">
                                @csrf
                                <div class="form-group row">
                                    <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>
                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                                        <span class="invalid-feedback">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Пароль') }}</label>
                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                        <span class="invalid-feedback">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Войти') }}
                                        </button>
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Забыли пароль?') }}
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCreateOrder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title text-center" id="myModalLabel">Подтверждение платежа</h4>
                <p class="text-center">Выберите способ оплаты:</p>
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="https://secure.platononline.com/payment/auth" method="post">
                                @csrf
                                <input type="hidden" name="payment" value=""/>
                                <input type="hidden" name="key" value=""/>
                                <input type="hidden" name="url" value=""/>
                                <input type="hidden" name="data" value=""/>
                                <input type="hidden" name="sign" value=""/>
                                <input type="hidden" name="ext1" value=""/>
                                <input type="hidden" name="ext2" value=""/>
                                <p>
                                    <button type="submit" class="btn btn-primary payment_btn" id="platon_btn">Купить <span></span></button>
                                    <span>+ {{env('PROC_PLATON')}}% комиссия</span>
                                </p>
                            </form>
                            {{--<form action="">
                                <input type="hidden" name="summ">
                                <p>
                                    <button type="button" class="btn btn-primary payment_btn" id="platon_btn">Platon 2 <span></span></button>
                                </p>
                            </form>--}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>