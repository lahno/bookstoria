<?php

namespace App\Http\Controllers;

use App\Book;
use App\Category;
use App\Chapter;
use App\Collection;
use App\FooterMenu;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LiqPay;

class ChaptersController extends Controller
{
   public function readChapter($book, $chapter)
   {
       if(isset($book) && !empty($book) && isset($chapter) && !empty($chapter))
       {
            $cats = Category::all();
            $cols = Collection::all();
            $foot = FooterMenu::all();
            $b = Book::find($book);
            if(isset($b) && !empty($b)){
                if($b->chapter_count > 0){
                    $chapters = $b->chapters($chapter)->get();
                    $chapter = Chapter::find($chapter);
                    $paybook = Order::where('book_id', $book)->where('result', 'success')->first();
                    if($chapter->number < $b->chapter_count || isset($paybook) && !empty($paybook))
                    {
                        $out =  strip_tags($chapter->text,'<p><a><br>');
                        return view('pages.chapter',[
                            'page'=>$b,
                            'cols'=>$cols,
                            'cats'=>$cats,
                            'chapter'=>$chapter,
                            'chapters'=>$chapters,
                            'chap'=>$out,
                            'foot'=>$foot
                        ]);
                    }else{
                        $book = Book::find($b->id);
                        $cats = Category::all();
                        $cols = Collection::all();
                        if(isset($book) && !empty($book)){
                            if (Auth::user()){
                                $order = new Order();
                                $order->user_id = Auth::user()->id;
                                if ($b->author) $order->author_id = $b->author->id;
                                elseif ($b->author_id) $order->author_id = $b->author_id;
                                $order->book_id = $b->id;
                                $order->save();
                                $html = '<br><a href="'.route('create_order', ['id' => $b->id]).'" class="btn book-btn btn-success" id="order_create" book-id="'.$b->id.'">Купить за '.$b->price.' ₽</a>';
                            }else{
                                $html = '<br><a href="'.route('create_order', ['id' => $b->id]).'" class="btn book-btn btn-success" id="order_user_create" book-id="'.$b->id.'">Купить за '.$b->price.' ₽</a>';
                            }
                            $out = "Доступ к данной главе только после покупки книги";
                            return view('pages.chapter',[
                                'page'=>$b,
                                'cols'=>$cols,
                                'cats'=>$cats,
                                'chapter'=>$chapter,
                                'chapters'=>$chapters,
                                'out'=>$out,
                                'html'=>$html,
                                'foot'=>$foot,
                            ]);
                        }

                    }
                }else{
                    $chapters = $b->chapters($chapter)->get();
                    $chapter = Chapter::find($chapter);
                    $out = strip_tags($chapter->text,'<p><a><br>');
                    return view('pages.chapter',[
                        'page'=>$b,
                        'cols'=>$cols,
                        'cats'=>$cats,
                        'chapter'=>$chapter,
                        'chapters'=>$chapters,
                        'chap'=>$out,
                        'foot'=>$foot
                    ]);
                }

            }
       }
   }

   public function readBook($book)
   {
       if(isset($book) && !empty($book))
       {
           $cats = Category::all();
           $cols = Collection::all();
           $foot = FooterMenu::all();
           $chapters = false;
           $b = Book::find($book);
           if ($b->chapters()){
               $chapters = $b->chapters()->get();
           }
           $chapter = Chapter::where('book_id',$b->id)->first();

           if(isset($chapter) && !empty($chapter)){

               $out =  strip_tags($chapter->text,'<p><a><br>');
               if(isset($b) && !empty($b))
               {
                   return view('pages.chapter',[
                       'page'=>$b,
                       'cols'=>$cols,
                       'cats'=>$cats,
                       'chapter'=>$chapter,
                       'chapters'=>$chapters,
                       'chap'=>$out,
                       'foot'=>$foot
                   ]);
               }
           }

           if(isset($b) && !empty($b))
           {
               return view('pages.chapter',[
                   'page'=>$b,
                   'cols'=>$cols,
                   'cats'=>$cats,
                   'chapter'=>$chapter,
                   'chapters'=>$chapters,
                   'foot'=> $foot,
               ]);
           }
       }
   }

}
