@extends('layouts.master')

@section('content')
    <div class="container main admin-main">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-3 left-sidebar wow fadeInLeft">
                @include('pages.usersidebar')
            </div>
            <div class="col-12 col-md-8 col-lg-9 content">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <img src="/{{$com->cover}}" width="100%" height="auto">
                    </div>
                    <div class="col-12 col-md-9">
                        <h3 class="book-single-titile">{{$com->name}}</h3>
                        <div class="row">

                            <div class="col-12 col-md-12">
                                <p class="single-book-tags">
                                @if($com->status == 1)
                                    <p style="font-size: 13px;"><strong>Статус: </strong>Регистрация открыта</p>
                                @else
                                    <p style="font-size: 13px;"><strong>Статус: </strong>Регистрация закрыта</p>
                                @endif
                                </p>
                                <p class="single-book-tags"><strong>Участников </strong>{{$com->comporders->count()}}</p>
                                <p class="single-book-tags"><strong>Создан: {{$com->created_at}}</strong> </p>
                                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#modalrules">
                                    Ознакомиться с правилами
                                </button> <br>
                                    @if(\Illuminate\Support\Facades\Auth::user())
                                        <button class="btn btn-outline-primary" type="button" data-toggle="collapse" data-target="#add_book_collapse" aria-expanded="false" aria-controls="add_book_collapse" style="margin-top: 10px;">
                                            Подать книгу на конкурс
                                        </button>
                                    @endif
                            </div>
                        </div>
                    </div>
                    <br>
                    @if(\Illuminate\Support\Facades\Auth::user())
                        <div class="collapse col-md-12" id="add_book_collapse">
                            <div class="card card-body">
                                <form method="POST" action="/competition/createorder">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="hidden" name="compid" value="{{$com->id}}">
                                            <select class="form-control" name="bookid" required>
                                                @if(isset($user->books) && !empty($user->books[0]))
                                                    @foreach($user->books as $book)
                                                        <option value="{{$book->id}}">{{$book->name}}</option>
                                                    @endforeach
                                                @else
                                                    <option disabled>Вы еще не добавили ни одной книги</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary">Отправить</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    <div class="col-12 col-md-12 ">
                        <p class="book-description">
                            {{$com->description}}
                        </p>
                    </div>
                </div>
                <div class="modal fade" id="modalrules" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Правила</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                               {!! $com->rules !!}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Назад</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <h4>Произведения</h4>
                    <hr>
                    @if(isset($com->comporders) && !empty($com->comporders))
                        @foreach($com->comporders as $b)
                            @if($b->books)
                            <div class="row wow fadeInUp justify-content-center">
                                <div class="col-5 col-md-3 catalog-item-col">
                                    <div class="catalog-book-item">
                                        <img src="/{{($b->books->cover)?$b->books->cover:''}}" width="100%" height="auto">
                                    </div>
                                </div>

                                <div class="col-12 col-md-9">
                                    <a href="#"><h4 class="book-title">{{$b->books->name}}</h4></a>
                                    <h6 class="book-title">{{$b->books->author_name}}</h6>
                                    {!!$b->books->annotation!!} <a href="/book/{{$b->books->id}}" class="read-more">Подробнее</a>
                                    <p class="book-tags"><strong>Жанры:</strong>
                                        @foreach($b->books->categories as $c)
                                            {{$c->name}}
                                        @endforeach
                                    </p>
                                    <p class="book-tags"><strong>Темматические подборки:</strong>
                                        @foreach($b->books->collections as $c)
                                            {{$c->name}}
                                        @endforeach
                                    </p>
                                    <div class="blog-panel">
                                        <div class="blog-stat">
                                            <i class="fas fa-eye"></i> <span> {{$b->books->count_views}}</span>
                                            <i class="fas fa-comments"></i> <span> {{$b->books->comments->count()}}</span>
                                            <i class="fas fa-bookmark"></i> <span> {{$b->books->libraries->count()}}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            @endif
                        @endforeach
                    @endif
                </div>

            </div>
        </div>
    </div>

@endsection
