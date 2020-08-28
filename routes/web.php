<?php

use Illuminate\Support\Facades\Route;

Route::get('/',                                         'LibraryController@homeView')->name('home');

Route::get('login',                                     'Auth\LoginController@showLoginForm')->name('login.view');
Route::post('login',                                    'Auth\LoginController@login')->name('login');
Route::post('logout',                                   'Auth\LoginController@logout')->name('logout');

Route::get('library',                                   'LibraryController@libraryView')->name('library.view');

Route::get('library/search',                            'LibraryController@search')->name('library.search');

Route::get('library/books/categories',                  'BookCategoriesController@allBookCategories')->name('books.categories');
Route::get('library/books/recently',                    'BooksController@recentlyPublishedBooks')->name('books.recently');
Route::get('library/books/random',                      'BooksController@getRandomBooks')->name('books.random');
Route::get('library/books/list',                        'BooksController@bookList')->name('books.list');
Route::get('library/books/related',                     'BooksController@relatedBooks')->name('books.related');
Route::get('library/books/{slug}',                      'BooksController@showBookView')->name('books.show');

Route::get('library/writers/{slug}',                    'WritersController@searchWriter')->name('writers.show');
Route::get('library/writers/search',                    'WritersController@searchWriter')->name('writers.search');

Route::get('admin',                                     'AdminController@adminView')->name('admin.view');
Route::get('admin/books',                               'BooksController@paginatedBookList')->name('admin.books');
Route::get('admin/books/search',                        'BooksController@searchBooks')->name('admin.books.find');
Route::post('admin/books/create',                       'BooksController@storeBook')->name('admin.books.store');
Route::get('admin/books/{id}/edit',                     'BooksController@editBook')->name('admin.books.edit');
Route::put('admin/books/{id}/update',                   'BooksController@updateBook')->name('admin.books.update');
Route::delete('admin/books/{id}/delete',                'BooksController@deleteBook')->name('admin.books.delete');

Route::get('admin/books/{id}/records',                  'BookRecordsController@allRecords')->name('admin.books.records');
Route::post('admin/books/{id}/records/store',           'BookRecordsController@storeRecord')->name('admin.books.records.store');
Route::put('admin/books/{book}/records/{id}/update',    'BookRecordsController@updateRecord')->name('admin.books.records.update');
Route::delete('admin/books/{book}/records/{id}/delete', 'BookRecordsController@deleteRecord')->name('admin.books.records.delete');

Route::get('admin/categories',                          'BookCategoriesController@list')->name('admin.categories');
Route::post('admin/categories/store',                   'BookCategoriesController@storeCategory')->name('admin.categories.store');
Route::put('admin/categories/{id}/update',              'BookCategoriesController@updateCategory')->name('admin.categories.update');
Route::delete('admin/categories/{id}/delete',           'BookCategoriesController@deleteCategory')->name('admin.categories.delete');

Route::get('admin/writers',                             'WritersController@writerList')->name('admin.writers');
Route::post('admin/writers/store',                      'WritersController@storeWriter')->name('admin.writers.store');
Route::put('admin/writers/{id}/update',                 'WritersController@updateWriter')->name('admin.writers.update');
Route::delete('admin/writers/{id}/delete',              'WritersController@deleteWriter')->name('admin.writers.delete');

Route::get('admin/books/borrowed',                      'BorrowedBooksController@list')->name('admin.books.borrowed');
Route::post('admin/books/borrows/store',                'BorrowedBooksController@storeBorrow')->name('admin.books.borrows.store');
Route::put('admin/books/borrows/{id}/back',             'BorrowedBooksController@giveBackBook')->name('admin.books.borrows.back');

Route::get('admin/users/search',                        'UsersController@searchUsers')->name('admin.users.find');

Route::get('mybooks',                                   'LibraryController@usersBooks')->name('admin.users.books.view');
Route::get('mybooks/records',                           'LibraryController@recordsOfUser')->name('admin.users.books.records');

// Auth::routes();