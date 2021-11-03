<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\Book;

class ListExport implements FromView
{
    protected $export_as;
    public function __construct($data) 
    {
        $this->export_as = $data;
    }
    public function view(): View 
    {
        if ($this->export_as == 1) {
            $lists = Book::orderBy('author')
                        ->get();
            return view('exports.title-author', [
                'lists' => $lists
            ]);
        }else if ($this->export_as == 2) {
            $lists = Book::select('title')
                        ->orderBy('title', 'ASC')
                        ->get();
            return view('exports.only-titles', [
                'lists' => $lists
            ]);
        }else {
            $lists = Book::select('author')
                        ->orderBy('author', 'ASC')
                        ->get();
            return view('exports.only-authors', [
                'lists' => $lists
            ]);
        }
    }
}
