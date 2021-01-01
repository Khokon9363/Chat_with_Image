@extends('layouts.app')

@section('content')
    <input type="hidden" id="authId" value="{{ auth()->user()->id }}">
    <input type="hidden" id="authName" value="{{ auth()->user()->name }}">
    <example-component></example-component>
@endsection
