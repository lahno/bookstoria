<?php

namespace App\Http\Controllers;

use App\Author;
use App\Book;
use App\Category;
use App\Chapter;
use App\Collection;

use App\FooterMenu;
use App\NotificationChapter;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Image;

class BooksController extends Controller
{
   public function add(Request $request)
   {
       if(isset($request) && !empty($request))
       {
           $user = Auth::user();
           foreach ($user->roles as $role){

               if($role->name == 'author' || $role->name == 'admin'){
                   $book = new Book();
                   $book->name = $request->name;
                   $book->author_name = $request->author;
                   $authors = explode(", ", $request->author);
                   if(count($authors)){
                       foreach ($authors as $author){
                           $author_t = Author::where('name', $author)->first();
                           if (!$author_t){
                                $new_author = new Author();
                                $new_author->name = $author;
                                $new_author->user_id = $user->id;
                                $new_author->save();
                           }
                       }
                   }
                   if ($request->hasfile('cover')){
                       $image = $request->file('cover');
                       $filename  = time() . rand() . '.' . $image->getClientOriginalExtension();
                       $location = public_path('images/covers/' . $filename);
                       Image::make($image)->save($location);
                       $book->cover = 'images/covers/' . $filename;
                   }
                   $book->annotation = strip_tags($request->annotation, '<p><a><br />');
                   if(isset($request->booktailer) && !empty($request->booktailer))
                   {
                       $book->booktailer = $request->booktailer;
                   }
                   $book->chapter_count = $request->chaptercount;
                   if ($request->price) $book->price = $request->price;
                   $book->public = $request->public;

//                   $author = Author::where('user_id', $user->id)->first();
//                   if ($author){
//                       $book->author_id = $author->id;
//                   }
                   $book->author_id = $user->id;

                   $book->save();
                   //$b = Book::find($book->id);
                   foreach ($request->cats as $cat)
                   {
                       $book->categories()->attach($cat);
                   }

                   if(isset($request->collections) && !empty($request->collections)){
                       foreach ($request->collections as $cat){
                           $book->collections()->attach($cat);
                       }
                   }

                   return redirect()->route('editbook', $book->id);
               }else{
                   return abort(403);
               }
           }

       }

       return abort(404);


   }

   public function edit(Request $request)
   {
       if(isset($request) && !empty($request)) {
           $user = Auth::user();
           foreach ($user->roles as $role) {

               if ($role->name == 'author' || $role->name == 'admin') {
                    if(isset($request->id) && !empty($request->id))
                    {
                        $book = Book::find($request->id);
                        if(isset($book) && !empty($book) && $book->author_id == Auth::user()->id)
                        {
                            $book->name = $request->name;
                            $book->author_name = $request->author;
                            $authors = explode(", ", $request->author);
                            if(count($authors)){
                                foreach ($authors as $author){
                                    $author_t = Author::where('name', $author)->first();
                                    if (!$author_t){
                                        $new_author = new Author();
                                        $new_author->name = $author;
                                        $new_author->user_id = $user->id;
                                        $new_author->save();
                                    }
                                }
                            }
                            if ($request->hasfile('cover')){
                                $image = $request->file('cover');
                                $filename  = time() . rand() . '.' . $image->getClientOriginalExtension();
                                $location = public_path('images/covers/' . $filename);
                                Image::make($image)->save($location);
                                $book->cover = 'images/covers/' . $filename;
                            }
                            $book->annotation = strip_tags($request->annotation, '<p><a><br />');
                            if(isset($request->booktailer) && !empty($request->booktailer))
                            {
                                $book->booktailer = $request->booktailer;
                            }
                            $book->chapter_count = $request->chaptercount;
                            $book->price = $request->price;
                            $book->public = $request->public;

                            $book->author_id = Auth::user()->id;
//                            $author = Author::where('user_id', $user->id)->first();
//                            if ($author){
//                                $book->author_id = $author->id;
//                            }

                            $book->tags = $request->tags;
                            $book->save();

                            if ($request->cats){
                                $book->categories()->detach($request->cats);
                                $book->categories()->attach($request->cats);
                            }
                            if ($request->collections){
                                $book->collections()->detach($request->collections);
                                $book->collections()->attach($request->collections);
                            }

                            return redirect()->route('edittbook', $book->id);
                        }
                    }
               }else{
                   return abort(403);
               }
           }
       }
   }

   public function delete($id)
   {
       if(isset($id) && !empty($id))
       {
           if(Auth::check()){
               $book  = Book::find($id);
               if(isset($book) && !empty($book) && $book->author->id == Auth::user()->id){

                   $book->chapters()->delete();
                   $book->comments()->delete();
                   $book->libraries()->delete();
                   $book->delete();
                   return redirect('mybooks');
               }
           }else{
               abort(403 , 'Access denied');
           }
       }
   }

   public function editBook($id)
   {
       if(isset($id) && !empty($id)){
           $book = Book::find($id);
           $cats = Category::all();
           $cols = Collection::all();
           $foot = FooterMenu::all();
           $chapters = Chapter::where('book_id',$id)->get();
          // dd($chapters);
           if(isset($book) && !empty($book))
           {
               return view('pages.editbook',[
                   'cats'=>$cats,
                   'cols'=>$cols,
                   'book'=>$book,
                   'chapters'=>$chapters,
                   'foot'=> $foot,
               ]);
           }
       }
   }

   public function editChapter(Request $request, $id){
       $chapter = Chapter::find($id);
       $user = Auth::user();
       $text = str_replace(['<br />'], "</p><p>", $request->text);
       foreach ($user->roles as $role){
           if($role->name == 'author' || $role->name == 'admin'){
               $chapter->name = $request->name;
               $chapter->text = strip_tags($text, '<p><a>');
               $chapter->number = $request->number;
               $chapter->author_id = $user->id;
               $chapter->book_id = $request->bookid;
               $chapter->save();
               return redirect()->back();
           }else{
               return abort(403,'Access Denied');
           }
       }
   }

    public function deleteChapter($id)
    {
        if(isset($id) && !empty($id))
        {
            if(Auth::check()){
                $chapter  = Chapter::find($id);
                if(isset($chapter) && !empty($chapter) && $chapter->author_id == Auth::user()->id){
                    $chapter->delete();
                    return redirect('mybooks');
                }
            }else{
                abort(403 , 'Access denied');
            }
        }
    }

   public function myBooks()
   {
       $user = Auth::user();
       foreach ($user->roles as $role){
           $cats = Category::all();
           $cols = Collection::all();
           $foot = FooterMenu::all();
           if($role->name == 'author' || $role->name == 'admin'){
             $books = Book::where('author_id', $user->id)->paginate(20);
                if(isset($books) && !empty($books))
                {
                    return view('pages.mybooks',[
                        'cats'=>$cats,
                        'cols'=>$cols,
                        'books'=>$books,
                        'foot'=> $foot
                    ]);
                }
               return view('pages.mybooks',[
                   'cats'=>$cats,
                   'cols'=>$cols,
                   'books'=>null,
                   'foot'=> $foot
               ]);
           }
       }
   }

   public function addChapter(Request $request)
   {
       if(isset($request) && !empty($request))
       {
            $chapter = new Chapter();
            $user = Auth::user();
            $text = str_replace(['<br />'], "</p><p>", $request->textchapter);
            foreach ($user->roles as $role){
                if($role->name == 'author' || $role->name == 'admin'){
                    $chapter->name = $request->namechapter;
                    $chapter->text = strip_tags($text, '<p><a>');
                    $chapter->number = $request->numberchapter;
                    $chapter->author_id = $user->id;
                    $chapter->book_id = $request->bookid;
                    $chapter->save();
                    $book = Book::find($request->bookid);
                    $not = new NotificationChapter();
                    $not->book_id = $request->bookid;
                    $not->message = 'Автор добавил новую главу к своей книге <a href="/book/'. $book->id .'">'.$book->name.'</a>' ;
                    $not->read = 0;
                    $not->save();
                    return redirect()->back();
                }else{
                    return abort(403,'Access Denied');
                }
           }
       }
   }

   public function getCat($id)
   {
       if(isset($id) && !empty($id))
       {
           $cat = Category::find($id);
           $books = $cat->books()->paginate(20);
           $cats = Category::all();
           $cols = Collection::all();
           $foot = FooterMenu::all();
           if(isset($books) && !empty($books))
           {
               return view('pages.cat',[
                   'cat'=>$cat,
                   'cats'=>$cats,
                   'books'=>$books,
                   'cols'=>$cols,
                   'foot'=> $foot,
               ]);
           }
       }

       return abort(404);
   }

   public function getCatAll()
   {
       $cats = Category::all();
       $books = Book::paginate(20);
       if ($books) $books->load('author');
       $cols = Collection::all();
       $foot = FooterMenu::all();
       if(isset($books) && !empty($books))
       {
           return view('pages.catall',[
               'cats'=>$cats,
               'books'=>$books,
               'cols'=>$cols,
               'foot'=>$foot
           ]);
       }
   }

   public function getCol($id)
   {
       if(isset($id) && !empty($id))
       {
           $cat = Collection::find($id);
           $cats = Category::all();
           $books = $cat->books()->paginate(20);
           $cols = Collection::all();
           $foot = FooterMenu::all();
           if(isset($cat) && !empty($cat))
           {
               return view('pages.collection',[
                   'cat'=>$cat,
                   'cats'=>$cats,
                   'cols'=>$cols,
                   'books'=>$books,
                   'foot'=>$foot
               ]);
           }
       }

       return abort(404);
   }

    public function getAuthorBooks($author_name){
        if(isset($author_name) && !empty($author_name))
        {
            $user = Author::where('name', 'like', '%'.$author_name.'%')->first();
            $cats = Category::all();
            $cols = Collection::all();
            $foot = FooterMenu::all();
//            $books = Book::where('author_name', $author_name)->paginate(20);

            $query = Book::select('*');
            $query->where(function($query) use ($author_name) {
                $query->where('author_name', 'like', '%' . $author_name . '%')
                    ->orWhere('author_name', 'like', '%' . $this->getStringLat($author_name) . '%');
            });
            $books = $query->paginate(20);

//            dd($books);

            if(isset($books) && !empty($books))
            {
                return view('pages.author_books',[
                    'cats'=>$cats,
                    'user'=>$user,
                    'cols'=>$cols,
                    'foot'=> $foot,
                    'books'=>$books,
                ]);
            }
        }

        return abort(404);
    }

   public function search(Request $request)
   {
       if(isset($request) && !empty($request->search))
       {
           $cats = Category::all();
           $cols = Collection::all();
           $foot = FooterMenu::all();
           $items = Book::where([
               ['name', 'LIKE', '%' . $request->search . '%'],
           ])->orWhere('author_name','LIKE','%' . $request->search . '%')
               ->orWhere('tags','LIKE','%' . $request->search . '%')
               ->get();

           return view('pages.search', [
               'cats'=>$cats,
               'cols'=>$cols,
               'cat'=>$items,
               'foot'=>$foot,
           ]);
       }
   }

   public function editB($id)
   {
       if(isset($id) && !empty($id))
       {
           $cats = Category::all();
           $cols = Collection::all();
           $book = Book::find($id);
           $foot = FooterMenu::all();
           if(isset($book) && !empty($book))
           {
               return view('pages.bookedit',[
                   'cats'=>$cats,
                   'cols'=>$cols,
                   'book'=>$book,
                   'foot'=>$foot
               ]);
           }
       }
       return abort(404,'not found');
   }

   public function getBook($id)
   {
       if(isset($id) && !empty($id))
       {
           $cats = Category::all();
           $cols = Collection::all();
           $book = Book::find($id);
           $foot = FooterMenu::all();
           if(isset($book) && !empty($book)){
               $book->count_views = $book->count_views + 1;
               $book->save();
           }
           if(isset($book) && !empty($book))
           {
               return view('pages.book',[
                   'cats'=>$cats,
                   'cols'=>$cols,
                   'book'=>$book,
                   'foot'=>$foot
               ]);
           }
       }
       return abort(404,'not found');
   }
}
