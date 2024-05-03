<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Books;
use App\Models\Categories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use Illuminate\Support\Facades\Auth;

class BooksController extends Controller
{

    public function getAll()
    {
        $books = Books::all();
        return BookResource::collection($books);
    }

    public function index()
    {
        $books = Books::all();
        return view("books", compact("books"));
    }

    public function add()
    {
        $categories = Categories::all();
        return view("add-book", compact("categories"));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required',
            'author' => 'required',
            'category_id' => 'required'
        ]);
        $book = Books::create($request->all());
        return redirect("books")->with("success", "Book added successfully..!");
    }

    public function edit($id)
    {
        $book = Books::find($id);
        $categories = Categories::all();
        return view("add-book", compact("book", 'categories'));
    }

    public function update(Request $request, $id)
    {
        $book = Books::find($id);
        $book->update($request->all());
        return redirect('books')->with('success', 'Book updated sucessfully..!');
    }

    public function delete($id)
    {
        Books::where("id", $id)->delete();
        return redirect("books")->with("success", "Deleted successfully..!");
    }

    public function list()
    {
        $user = Auth::user();
        $cart = Cart::with('cartdetails')->where("user_id", $user->id)->first();

        $books = Books::all();
        return view("book-list", compact("books", 'cart'));
    }

}
