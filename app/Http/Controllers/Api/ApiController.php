<?php

namespace App\Http\Controllers\Api;

use App\Author;
use App\Book;
use App\Chapter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function getChapters(Request $request, $id)
    {
        $b = Book::find($id);
        $chapters = Chapter::select('id', 'name', 'number', 'public')->where('book_id', $id)->get();
        if ($chapters && $b){
            return response()->json([
                'success' => true,
                'count' => count($chapters),
                'bookname' => $b->name,
                'author' => $b->author_name,
                'data' => $chapters
            ], 200)->header('Access-Control-Allow-Origin', '*');
        }
        return response()->json([
            'success' => false,
            'description' => 'not found book id '.$id
        ], 200)->header('Access-Control-Allow-Origin', '*');
    }
    public function getChapter(Request $request, $id)
    {
        $chapter = Chapter::find($id);
        if ($chapter){
            $text = $this->chapterReader($chapter->text);
//            $text = str_replace(["\r\n","\n","\x0A\x0D","\x0D\x0A","\x0A","\x0D"], "<br>", $text);
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $chapter->id,
                    'name' => $chapter->name,
                    'number' => $chapter->number,
                    'book_id' => $chapter->book_id,
                    'author_id' => $chapter->author_id,
                    'public' => $chapter->public,
                    'text' => $text,
                ]
            ], 200)->header('Access-Control-Allow-Origin', '*');
        }
        return response()->json([
            'success' => false,
            'description' => 'not found chapter id '.$id
        ], 200)->header('Access-Control-Allow-Origin', '*');
    }

    function chapterReader($text, $limitChars = 5000)
    {
        $textSize = mb_strlen($text);
        $pageCount = ceil($textSize / $limitChars);
        $averagePageSize = ceil($textSize / $pageCount);

        $pages = [];

        for ($i = 0; $i < $pageCount; $i++)
        {
            $currentPage = mb_substr($text, $averagePageSize * $i, $averagePageSize);
            $pages[] = $currentPage;
        }

        for ($i = 1; $i < $pageCount; $i++)
        {
            $currentPage = $pages[$i - 1];
            $nextPage = $pages[$i];

            if (mb_substr($currentPage, -4, 4) !== '</p>')
            {
                $tagPosition = mb_strpos($nextPage, '</p>') + 4;
                $currentPage .= mb_substr($nextPage, 0, $tagPosition);
                $nextPage = trim(mb_substr($nextPage, $tagPosition));
            }

            $pages[$i - 1] = $currentPage;
            $pages[$i] = $nextPage;
        }

        return $pages;
    }

    public function getAuthors(){
        $authors = Author::all()->pluck('name');

        return response()->json([
            'success' => true,
            'data' => $authors
        ], 200)->header('Access-Control-Allow-Origin', '*');
    }

    public function addAuthors(){
        $books = Book::all();
        foreach ($books as $book){
            $authors = explode(", ", $book->author_name);
            if(count($authors)){
                foreach ($authors as $author){
                    $author_t = Author::where('name', $author)->first();
                    if (!$author_t){
                        $new_author = new Author();
                        $new_author->name = $author;
                        $new_author->save();
                    }
                }
            }
        }
        return response()->json(['success' => true], 200);
    }
}
