<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\BookResource;

class BookController extends Controller
{
    public function index()
    {
        return BookResource::collection(Book::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_copies' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $book = Book::create([
            'title' => $request->title,
            'description' => $request->description,
            'total_copies' => $request->total_copies,
            'available_copies' => $request->total_copies,
        ]);

        return response()->json([
            'message' => 'Book successfully created',
            'book' => new BookResource($book)
        ], 201);
    }

    public function show(Book $book)
    {
        return new BookResource($book);
    }

    public function update(Request $request, Book $book)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_copies' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $book->update([
            'title' => $request->title,
            'description' => $request->description,
            'total_copies' => $request->total_copies,
            'available_copies' => $request->total_copies,
        ]);

        return response()->json([
            'message' => 'Book successfully updated',
            'book' => new BookResource($book)
        ]);
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json([
            'message' => 'Book successfully deleted'
        ], 200);
    }
}