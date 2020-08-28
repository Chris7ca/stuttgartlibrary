@extends('layouts.app')

@section('title_page', $book->title)

@section('content_page')

    <section class="uk-container uk-container-large uk-margin-large">

        <div class="uk-grid" uk-grid>
            <div class="uk-width-auto@m">
                <div class="uk-text-center">
                    <img class="uk-border-rounded uk-width-medium uk-box-shadow-large" data-src="{{ $book->image }}" uk-img>
                </div>
            </div>
            <div class="uk-width-expand@m">
                <div>
                    <h1 class="uk-heading-small uk-margin-remove">{{ $book->title }}</h1>
                    <h2 class="uk-margin-small">
                        by <a class="uk-text-primary" href="{{ route('library.view', ['author' => $book->author_slug]) }}">{{ $book->author }}</a>
                    </h2>
                    <p>
                        <span class="uk-label uk-label-primary uk-margin-small-right">{{ $book->category }}</span>
                        @if ( Auth::user() && $book->borrowed_books == $book->existences )
                            <span class="uk-label uk-label-danger">Book not available</span>
                        @elseif ( Auth::user() && $book->borrowed_books < $book->existences )
                            <span class="uk-label uk-label-success">{{ $book->existences - $book->borrowed_books }} books availables</span>
                        @endif
                    </p>
                    <p>
                        {{ $book->synopsis }}
                    </p>
                </div>
            </div>
        </div>

    </section>

    <section class="uk-container uk-container-large uk-margin-large">
        <h2 class="uk-heading-small">Related books</h2>
        <slider-books :resource-route="'{{ route('books.related', ['category' => $book->category_slug]) }}'"></slider-books>
    </section>

@endsection

@push('js')
    <script src="{{ asset('js/app.js') }}"></script>
@endpush