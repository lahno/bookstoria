@extends('layouts.master')

@section('content')
    <div class="container main admin-main">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-3 left-sidebar wow fadeInLeft">
                @include('pages.usersidebar')
            </div>

            <div class="col-12 col-md-8 col-lg-9 content" style="margin-top: 60px;">

                <div class="row wow justify-content-center">

                    <div class="col-12">
                        <h2 class="cat-title">Мои финансы</h2>
                    </div>
                    <div class="col-12">
                        <h2 class="cat-title">История операций</h2>
                    </div>

                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Дата</th>
                            <th scope="col">Сумма</th>
                            <th scope="col">Операция</th>
                            <th scope="col">Прочее</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($orders))
                            @foreach($orders as $order)
                                @if($order->result == 'success')
                                <tr>
                                    <th scope="row">{{$order->created_at}}</th>
                                    <td>{{$order->summ}}</td>
                                    <td>{{$order->paytype}}</td>
                                    <td>{{$order->description}}</td>
                                </tr>
                                @endif
                            @endforeach
                            <tr>
                                <th scope="row">Всего</th>
                                <td>{{$sum}}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endif

                        </tbody>
                    </table>
                </div>



            </div>
        </div>
    </div>
@endsection
