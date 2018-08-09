<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'PagesController@getFront')->name('index');

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // add Chapters
    Route::get('/home', 'PagesController@index')->name('home');
    //book
    Route::post('/book/add', 'BooksController@add')->name('addbook');
    Route::get('/mybooks', 'BooksController@myBooks')->name('mybooks');
    Route::get('/book/edit/{id}','BooksController@editB')->name('edittbook');
    Route::get('/book/delete/{id}','BooksController@delete')->name('deletebook');
    Route::post('/book/edit', 'BooksController@edit');
    //chapter
    Route::post('/chapter/add', 'BooksController@addChapter')->name('addchapter');
    Route::get('/chapter/edit/{id}', 'BooksController@editBook')->name('editbook');
    Route::post('/chapter/edit_chapter/{id}', 'BooksController@editChapter')->name('editChapter');
    Route::post('/chapter/delete/{id}', 'BooksController@deleteChapter')->name('deleteChapter');
    //library
    Route::get('/library/add/{id}', 'LibraryController@add')->name('libadd');
    Route::get('/mylibrary', 'LibraryController@libraryGet')->name('lib');
    Route::get('/library/delete/{id}', 'LibraryController@delete')->name('libdel');
    //Blogs
    Route::get('/myblogs', 'BlogsController@myBlogs');
    Route::get('/blog/add', 'BlogsController@addBlog');
    Route::post('/blog/addblog', 'BlogsController@addB');
    Route::get('/blog/edit/{id}', 'BlogsController@editBlog');
    Route::get('/blog/delete/{id}', 'BlogsController@deleteBlog');
    Route::post('/blog/editblog','BlogsController@blogEdited');

    //Statistic
    Route::get('/statistic', 'PagesController@Statistic');
    Route::get('/mypurchases', 'PagesController@myPurchases');
    Route::get('/myfinance', 'PagesController@myFinance');

});

//book
Route::get('/book/{id}', 'BooksController@getBook')->name('getbook');
Route::get('/allbook', 'BooksController@getCatAll')->name('getcatall');

//chapter
Route::get('/chapter/read/{book}/{chapter}', 'ChaptersController@readChapter')->name('readchapter');
Route::get('/chapter/read/{book}', 'ChaptersController@readBook')->name('readbook');

//category
Route::get('/category/{id}', 'BooksController@getCat')->name('getcat');

//collection
Route::get('/collections/{id}', 'BooksController@getCol')->name('getcol');

//Author books
Route::get('/author_books/{author_name}', 'BooksController@getAuthorBooks')->name('author_books');

//search
Route::get('/search', 'BooksController@search')->name('search');

//comment
Route::post('/comment/add', 'CommentsController@add')->name('commentadd');

//pages
Route::get('/page/{id}', 'PagesController@getPage')->name('pages');

// OAuth Routes
Route::get('/login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('/login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

//Blogs
Route::get('/blogs', 'BlogsController@getBlogs');
Route::get('/blog/{id}', 'BlogsController@getBlog');
Route::post('/commentblog/add', 'BlogsController@addComment');

//Competition
Route::get('/competition/all', 'CompetitionController@getAll');
Route::get('/competition/{id}', 'CompetitionController@getComp');
Route::post('/competition/createorder', 'CompetitionController@createOrder');

//Orders
Route::get('/order/create/{id}', 'OrderController@createOrder')->name('create_order');
//Order
Route::post('/order/accept/platon','OrderController@acceptOrder');

// API
Route::prefix('api')->group(function () {
    Route::get('/getChapters/{id}', 'Api\ApiController@getChapters');
    Route::get('/getChapter/{id}', 'Api\ApiController@getChapter');
    Route::get('/getAuthors', 'Api\ApiController@getAuthors');
    Route::get('/addAuthors', 'Api\ApiController@addAuthors');

    Route::middleware(['guest'])->group(function () {
        //quickRegister
//        Route::post('/quickRegister','Api\ApiController@quickRegister')->name('quickRegister');
    });
});


// Log view
Route::get('l_v_', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');