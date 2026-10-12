<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Book::all(), 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['required', 'integer', 'min:0', 'max:4294967295'],
        ]);

        $book = Book::create($validated);

        return response()->json($book, 201);
    }

    public function show(string $id): JsonResponse
    {
        $book = Book::find($id);

        if (! $book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        return response()->json($book, 200);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $book = Book::find($id);

        if (! $book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $validated = $request->validate([
            'title' => ['sometimes', 'required_without_all:description,status', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'status' => ['sometimes', 'integer', 'min:0', 'max:4294967295'],
        ]);

        $book->update($validated);

        return response()->json($book, 200);
    }

    public function destroy(string $id): JsonResponse
    {
        $book = Book::find($id);

        if (! $book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $book->delete();

        return response()->json(['message' => 'Book deleted'], 200);
    }
}
