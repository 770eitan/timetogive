@extends('layouts.app')

@section('title', $title)

@section('content')
    @include('home.header')
    @include('home.payment-deposit')
@endsection