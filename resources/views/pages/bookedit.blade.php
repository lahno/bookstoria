@extends('layouts.master')

@section('content')
    <div class="container main admin-main">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-3 left-sidebar wow fadeInLeft">
                @include('pages.usersidebar')
            </div>
            @php
                $selcat = $book->categories->pluck('id')->toArray();
                $selpod = $book->collections->pluck('id')->toArray();
            @endphp
            <div class="col-12 col-md-8 col-lg-9 content" style="margin-top: 60px;">
                <div class="row justify-content-center">
                    <div class="col-10">
                        <h2 class="cat-title">Редактировать книгу</h2>
                        <form class="wow fadeInUp" method="POST" action="/book/edit" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="{{$book->id}}" name="id">
                            <div class="form-row justify-content-center">
                                <div class="form-group col-md-12">
                                    <label for="name">Название книги:</label>
                                    <input type="text" id="name" class="form-control" name="name" placeholder="Название книги" value="{{$book->name}}" required>
                                </div>
                                <div class="form-group col-md-12">
                                    {{--<input type="text" class="form-control" name="author" placeholder="Имя автора" value="{{$book->author_name}}" required>--}}
                                    <div class="ui-widget">
                                        <label for="tags">Имя автора:</label>
                                        <input id="tags" class="form-control" name="author" size="50" value="{{$book->author_name}}" required>
                                    </div>
                                </div>
                                <div class="form-group custom-file-block col-md-12" style="margin-top: 35px; margin-left: 5px;">
                                    <input type="file" class="custom-file-input" id="customFile" name="cover" lang="es">
                                    @if(isset($book->cover))
                                        <label class="custom-file-label" id="label_file" for="customFile" style="background-image: url('/{{$book->cover}}');"></label>
                                    @else
                                        <label class="custom-file-label" id="label_file" for="customFile"></label>
                                    @endif
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="annotacia">Аннотация:</label>
                                    <textarea class="form-control" id="annotacia" rows="8" name="annotation" placeholder="Аннотации"  required>{{$book->annotation}}</textarea>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="FormControlSelect1">Жанры</label>
                                    <select class="form-control js-example-basic-multiple" multiple name="cats[]" id="cats" required>
                                        @foreach($cats as $cat)
                                            <option value="{{$cat->id}}"
                                            @if(in_array($cat->id,$selcat))
                                             selected="selected"
                                            @endif
                                            >{{$cat->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        let last_valid_selection = null;
                                        $('#cats').change(function(event) {
                                            if ($(this).val().length > 3) {
                                                $(this).val(last_valid_selection);
                                                alert('Возможно указать не больше 3 жанров');
                                            }
                                        });
                                    });
                                </script>

                                <div class="form-group col-md-6">
                                    <label for="FormControlSelect2">Темматические подборки</label>
                                    <select class="form-control js-example-basic-multiple" multiple name="collections[]" id="collections">
                                        @foreach($cols as $col)
                                            <option value="{{$col->id}}"
                                               @if(in_array($col->id,$selpod))
                                               selected="selected"
                                               @endif
                                            >{{$col->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        let last_valid_selection = null;
                                        $('#collections').change(function(event) {
                                            if ($(this).val().length > 5) {
                                                $(this).val(last_valid_selection);
                                                alert('Возможно указать не больше 5 тематических подборок');
                                            }
                                        });
                                    });
                                </script>
                                <div class="form-group col-md-12">
                                    <textarea class="form-control" id="exampleFormControlTextarea1" name="tags"  rows="4" placeholder="Теги (через запятую)">{{$book->tags}}</textarea>
                                </div>
                                <div class="form-group col-md-12">
                                    <textarea class="form-control" name="booktailer" id="exampleFormControlTextarea1" rows="1" placeholder="Ссылка на буктрейлер">{{$book->booktailer}}</textarea>
                                </div>
                                <div class="form-group col-md-12" style="text-align: center;">
                                    <input type="text" class="form-control" name="chaptercount" placeholder="С какой главы платная? 0 - бесплатная" value="{{$book->chapter_count}}"required>
                                </div>
                                {{--<div class="form-group col-md-12" style="text-align: center;">
                                    <input type="text" class="form-control" name="price" placeholder="Цена" value="{{$book->price}}">
                                </div>--}}
                                <div class="form-group col-md-12" style="text-align: center;">
                                    <input class="form-check-input" name="translated" type="checkbox" value="1" id="defaultCheck1">
                                    <label class="form-check-label" for="defaultCheck1" >
                                        Это перевод
                                    </label>
                                </div>
                                <div class="form-group col-md-12" style="text-align: center;">
                                    <input class="form-check-input" name="public" type="checkbox" checked value="1" id="defaultCheck2">
                                    <label class="form-check-label" for="defaultCheck2">
                                        Разрешить показ
                                    </label>
                                </div>

                                <div class="form-group col-md-12" style="text-align: center;">
                                    <input class="form-check-input" type="checkbox" checked value="1" id="defaultCheck3" required>
                                    <label class="form-check-label" for="defaultCheck3" >
                                        Вы согласны с правилами пользования сервисом?
                                    </label>
                                </div>
                                <div class="form-group col-md-12" style="text-align: center;">
                                    <button type="submit" class="btn btn-primary">Обновить книгу</button>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>


            </div>
        </div>
    </div>

    <script>
        document.querySelector("#customFile").addEventListener("change", function () {
            if (this.files[0]) {
                var fr = new FileReader();

                fr.addEventListener("load", function () {
                    document.querySelector("#label_file").style.backgroundImage = "url(" + fr.result + ")";
                }, false);

                fr.readAsDataURL(this.files[0]);
            }
        });
    </script>

    {{--Автокомплит, мультиселект АВТОР--}}
    <script>
        $( function() {
            let availableTags = [];
            function split( val ) {
                return val.split( /,\s*/ );
            }
            function extractLast( term ) {
                return split( term ).pop();
            }
            $.ajax({
                type: 'GET',
                url: '/api/getAuthors',
                data: '',
                processData: true,
                contentType: false,
                success: function(data) {
                    console.log(data.data);
                    if(data.success){
                        availableTags = data.data;
                    }
                }
            });

            $( "#tags" )
            // don't navigate away from the field on tab when selecting an item
                .on( "keydown", function( event ) {
                    if ( event.keyCode === $.ui.keyCode.TAB &&
                        $( this ).autocomplete( "instance" ).menu.active ) {
                        event.preventDefault();
                    }
                })
                .autocomplete({
                    minLength: 0,
                    source: function( request, response ) {
                        // delegate back to autocomplete, but extract the last term
                        response( $.ui.autocomplete.filter(
                            availableTags, extractLast( request.term ) ) );
                    },
                    focus: function() {
                        // prevent value inserted on focus
                        return false;
                    },
                    select: function( event, ui ) {
                        let terms = split( this.value );
                        // remove the current input
                        terms.pop();
                        // add the selected item
                        terms.push( ui.item.value );
                        // add placeholder to get the comma-and-space at the end
                        terms.push( "" );
                        this.value = terms.join( ", " );
                        return false;
                    }
                });
        } );
    </script>
@endsection
