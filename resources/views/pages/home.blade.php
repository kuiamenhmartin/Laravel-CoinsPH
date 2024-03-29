@extends('layouts.app')

@section('title', 'To do app')

@section('content')
<div class="container" id="app">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <passport-authorized-clients></passport-authorized-clients>
            <passport-clients></passport-clients>
            <passport-personal-access-tokens></passport-personal-access-tokens>
        </div>
    </div>
</div>
@endsection
