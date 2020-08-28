@extends('errors::minimal')

@section('title', $exception->getMessage() ?: 'Not found')
@section('code', '404')
@section('message', $exception->getMessage() ?: 'Not found')
