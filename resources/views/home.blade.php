@extends('layouts.app')

@section('content')
    <input type="hidden" id="authId" value="{{ auth()->user()->id }}">
    <example-component></example-component>
@endsection
