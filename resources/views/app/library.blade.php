@extends('layouts.app')

@section('title_page', 'Library')

@push('css')
    <link href="{{ asset('css/slimselect.min.css') }}" rel="stylesheet">
@endpush

@section('content_page')
    
    <section class="uk-container uk-container-large uk-margin-large">
        
        <h1 class="uk-heading-small uk-text-bold uk-margin-small">All Books</h1>
        <hr class="uk-divider-small">

        <library-navigation :category="'{{ request('category') }}'" :author="'{{ request('author') }}'"></library-navigation>

    </section>

@endsection

@push('js')
    <script src="{{ asset('js/library.js') }}"></script>
@endpush