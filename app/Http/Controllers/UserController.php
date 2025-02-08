<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowedBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function availableBooks()
    {
        return Book::where('available_copies', '>', 0)->get();
    }

    public function borrowBook(Request $request, Book $book)
    {
        $validator = Validator::make($request->all(), [
            'return_date' => 'required|date|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($book->available_copies <= 0) {
            return response()->json(['error' => 'No copies available'], 400);
        }

        BorrowedBook::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'borrow_date' => now(),
            'return_date' => $request->return_date,
        ]);

        $book->decrement('available_copies');

        return response()->json([
            'message' => 'Book successfully borrowed'
        ], 200);
    }

    public function returnBook(Book $book)
    {
        $borrowedBook = BorrowedBook::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->where('returned', false)
            ->first();

        if (!$borrowedBook) {
            return response()->json(['error' => 'You did not borrow this book'], 400);
        }

        $borrowedBook->update(['returned' => true]);

        $book->increment('available_copies');

        return response()->json([
            'message' => 'Book successfully returned'
        ], 200);
    }
}