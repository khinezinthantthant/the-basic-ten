@extends('layouts.master')

@section('title')
    Home Page
@endsection


@section('content')
    <h2>I'm Home Page</h2>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab eveniet accusantium dolores deserunt ad dignissimos numquam nostrum, reiciendis laboriosam aut.</p>

     <div class="alert alert-info">
        {{-- {{ route('item.show',[15,"a" => "aaa","b"=>"bbb"]) }} --}}
        {{ route('multi',[5,10,"a"=>"aaa","b"=>"bbb"]) }}
     </div>
@endsection
