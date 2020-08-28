@extends('layouts.app')

@section('title_page', 'Home')

@section('content_page')

    <main class="uk-container uk-container-large uk-margin-large">
        <div class="uk-grid" uk-grid>

            <div class="uk-width-1-3@m">
                <div>
                    <h2 class="uk-h1">Categories</h2>
                    <hr class="uk-divider-small">
                    <bookcategories-list></bookcategories-list>
                </div>
            </div>

            <div class="uk-width-2-3@m">
                <div>
                    <div class="uk-card uk-card-body uk-background-muted uk-card-bordered uk-position-relative" uk-height-viewport="offset-bottom: 50;">
                        <div>
                            <h1 class="uk-heading-medium uk-text-bold uk-margin-small">Recently published</h1>

                            <recently-published-books></recently-published-books>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <section class="uk-container uk-container-large uk-margin-large">
        <h2 class="uk-heading-small uk-text-bold uk-margin-small">Recommended Books</h2>
        <hr class="uk-divider-small">
        <slider-books :resource-route="'{{ route('books.random') }}'"></slider-books>
    </section>

@endsection

@push('js')
    <script src="{{ asset('js/app.js') }}"></script>
@endpush