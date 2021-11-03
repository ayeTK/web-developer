<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;
use App\Exports\ListExport;
use Maatwebsite\Excel\Facades\Excel;


class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::orderBy('author')
                    ->get();
        return response()->json(['bookList' => $books], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'required' => 'The :attribute field is required',
        ];
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'author' => 'required'
        ],$messages);

        if($validator->fails()){
            return response()->json(['msg' => $validator->errors()], 200);
        }else{
            $book = Book::create([
                'title' => $request->title,
                'author' => $request->author
            ]);
            return response()->json(['book' => $book, 'message' => 'Data created successfully'], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(['book' => Book::find($id)], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $book->update([
            'author' => $request->author
        ]);
        return response()->json(['message' => 'Data updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        $book_list = Book::all();
        return response()->json(['deletedBook' => $book, 'bookList' => $book_list, 'message' => 'Data deleted successfully.'], 200);
    }
    public function search(Request $request)
    {
        $search_word = $request->searchWord;
        $book_list = Book::where('title', 'like', '%'.$search_word.'%')
                            ->orWhere('author', 'like', '%'.$search_word.'%')
                            ->orderBy('author')
                            ->get();
        return response()->json(['bookList' => $book_list], 200);
    }
    public function csvExport($selected_value) 
    {
        $export_as = $selected_value;
        if($export_as == 1) {
            $file_name = 'title-author-list';
        }else if ($export_as == 2) {
            $file_name = 'title-list';
        }else {
            $file_name = 'author-list';
        }
        return Excel::download(new ListExport($export_as), $file_name.'.csv');
    }
    public function xmlExport($selected_value)
    {
        $export_as = $selected_value;
        if($export_as == 1) {
            $lists = Book::select('title', 'author')
                        ->orderBy('author')
                        ->get();
        }else if($export_as == 2) {
            $lists = Book::select('title')
                        ->get();
        }else {
            $lists = Book::select('author')
                        ->get();
        }
        return response()->xml(['lists' => $lists->toArray()]);
    }
}
