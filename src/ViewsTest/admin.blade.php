@extends('laravel-cms::layout')
@section('content')

<div id="admin-app">

    <router-view
        :user="{{ json_encode($user) }}"
        :cms="cms">
    </router-view>

</div>

@endsection
