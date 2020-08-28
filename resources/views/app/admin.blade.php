@extends('layouts.app')

@section('title_page', 'Admin')

@push('css')
    <link href="{{ asset('css/slimselect.min.css') }}" rel="stylesheet">
@endpush

@section('content_page')
    
    <section class="uk-container uk-container-large uk-margin-large">
        
        <h1 class="uk-heading-small uk-text-bold uk-margin-small">Manage your library</h1>
        <hr class="uk-divider-small">

        <div class="uk-grid uk-grid-medium uk-margin-large" uk-grid>
            <div class="uk-width-auto@m">
                <div>
                    <ul class="uk-tab-left uk-child-width-expand" uk-tab="connect: #menu-admin; animation: uk-animation-slide-left-small, uk-animation-slide-right-small">
                        <li class="uk-margin-small-top">
                            <a role="button">Books</a>
                        </li>
                        <li class="uk-margin-small-top">
                            <a role="button">Categories</a>
                        </li>
                        <li class="uk-margin-small-top">
                            <a role="button">Writers</a>
                        </li>
                        <li class="uk-margin-small-top">
                            <a role="button">Borrowed Books</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="uk-width-expand@m">
                <div>
                    <ul id="menu-admin" class="uk-switcher switcher-container">
                        <li>
                            <books-table></books-table>
                        </li>
                        <li>
                            <categories-table></categories-table>
                        </li>
                        <li>
                            <writers-table></writers-table>
                        </li>
                        <li>
                            <borrowed-books-table></borrowed-books-table>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </section>

    <div id="modal-book" class="uk-flex-top" uk-modal="esc-close: false; bg-close: false">
        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">

            <button class="uk-modal-close-default" type="button" uk-close></button>
    
            <h3>Complete de following information</h3>
            
            <form-book></form-book>
            
        </div>
    </div>

    <div id="modal-book-records" class="uk-flex-top" uk-modal="esc-close: false; bg-close: false">
        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">

            <button class="uk-modal-close-default" type="button" uk-close></button>
    
            <h3>All records of book</h3>

            <book-records></book-records>
            
        </div>
    </div>

    <div id="modal-category" class="uk-flex-top" uk-modal="esc-close: false; bg-close: false">
        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">

            <button class="uk-modal-close-default" type="button" uk-close></button>
    
            <h3>All categories</h3>

            <form-category></form-category>
            
        </div>
    </div>

    <div id="modal-writer" class="uk-flex-top" uk-modal="esc-close: false; bg-close: false">
        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">

            <button class="uk-modal-close-default" type="button" uk-close></button>
    
            <h3>All Writers</h3>

            <form-writer></form-writer>
            
        </div>
    </div>

    <div id="modal-borrow" class="uk-flex-top" uk-modal="esc-close: false; bg-close: false">
        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">

            <button class="uk-modal-close-default" type="button" uk-close></button>
    
            <h3>Information about borrow</h3>

            <borrow-book></borrow-book>
            
        </div>
    </div>

@endsection

@push('js')
    <script src="{{ asset('js/admin.js') }}"></script>
@endpush