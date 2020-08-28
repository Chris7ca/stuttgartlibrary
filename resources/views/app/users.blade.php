@extends('layouts.app')

@section('title_page', 'My books')

@section('content_page')
    
    <section class="uk-container uk-container-large uk-margin-large">
        
        <h1 class="uk-heading-small uk-text-bold uk-margin-small">Manage your library</h1>
        <hr class="uk-divider-small">

        <table id="table-users-books" class="uk-table uk-table-divider uk-table-responsive">
            <thead>
                <tr>
                    <td>Book</td>
                    <td>Caregory</td>
                    <td>Author</td>
                    <td>ISBN</td>
                    <td>Delivery<br>Date</td>
                    <td>Deadline</td>
                    <td>Status</td>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $record)
                    <tr class="{{ $record->return_date == null ? 'uk-background-warning' : '' }}">
                        <td>{{ $record->book }}</td>
                        <td>{{ $record->category }}</td>
                        <td>{{ $record->author }}</td>
                        <td>{{ $record->isbn }}</td>
                        <td>{{ $record->delivery_date }}</td>
                        <td>{{ $record->deadline }}</td>
                        <td>{{ $record->return_date ? "Returned at {$record->return_date}" : 'Pending return' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <h3>Not records found</h3>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="uk-margin">
            {{ $records->links('components.pagination') }}
        </div>

    </section>

@endsection

@push('js')
    <script src="{{ asset('js/users.js') }}"></script>
@endpush