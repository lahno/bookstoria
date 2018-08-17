
@extends('layouts.master')

@section('content')
    <div class="container main ">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-3 left-sidebar wow fadeInLeft">
                @include('layouts.sidebar')
            </div>

            <div class="col-12 col-md-8 col-lg-9 content">
                <div class="row">
                    <div class="col-12">
                        <h2 class="cat-title">{{$blog->name}}</h2>
                        <div class="blog-panel ">
                            <div class="blog-stat border-top border-bottom" style="width: 100%; margin-bottom: 10px;">
                                @if($blog->user)
                                    <span class="book-tags">{{$blog->user->name}}</span>
                                @endif
                                <span class="book-tags"> | </span>
                                <span class="book-tags">Дата публикации: {{$blog->created_at}}</span>
                                <span class="book-tags"> | </span>
                                <span class="book-tags"> Комментариев:</span>
                                <span class="book-tags"> {{$blog->comments->count()}}</span>
                            </div>
                        </div>

                    </div>
                    <div class="col-12">
                        {!! $blog->text !!}
                    </div>
                @if(\Illuminate\Support\Facades\Auth::check())

                    <!-- Комментарии-->

                        <div class="col-12">

                            <h4>Комментарии</h4>

                            <hr>

                            <div class="row justify-content-between">

                                <p class="book-tags col-6">Комментариев: {{$blog->comments->count()}}</p>

                                <p class="col-6 text-right">

                                    <button class="btn btn-outline-primary" type="button" data-toggle="collapse" data-target="#add_comment" aria-expanded="false" aria-controls="add_comment" >

                                        Комментировать

                                    </button>

                                </p>

                                <div class="collapse" id="add_comment">

                                    <div class="card card-body">

                                        <form method="POST" action="/commentblog/add">

                                            <div class="form-row justify-content-center">

                                                <input type="hidden" name="blog" value="{{$blog->id}}"/>

                                                @csrf

                                                <div class="form-group col-md-12">

                                                    <textarea class="form-control" name="comment" rows="6" cols="100" placeholder="Комментарий"></textarea>

                                                </div>



                                                <div class="form-group col-md-4" style="text-align: center;">

                                                    <button type="submit" class="btn btn-primary">Опубликовать</button>

                                                </div>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endif

                    <div class="col-md-12">

                        <h4>Комментарии</h4>

                        @foreach($blog->comments as $comment)

                            <div class="row comment-item">

                                <i class="fa fa-user-circle" style="font-size: 50px"></i>

                                <div class="comment-info col-10">

                                    @if(isset($commen->user))
                                        <h5> {{$comment->user->name}}</h5>
                                    @endif

                                    <span class="book-tags">Дата публикации: {{$comment->created_at}}</span>

                                    <p class="comment">

                                        {{$comment->text}}

                                    </p>

                                </div>

                            </div>

                        @endforeach

                    </div>



                </div>




            </div>
        </div>
    </div>

@endsection