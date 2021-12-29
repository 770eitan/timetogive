@extends('layouts.app')

@section('title', $title)

@section('content')
    {{-- @include('home.header') --}}
    @include('thankyou.message')
@endsection