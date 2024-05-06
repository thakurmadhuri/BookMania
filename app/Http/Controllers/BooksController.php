<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Books;
use App\Models\Categories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BooksController extends Controller
{
    public function getAll()//book api
    {
        $books = Books::all();
        return BookResource::collection($books);
    }

    public function getOne($id)//get one book
    {
        $book = Books::find($id);
        return $book;
    }

    public function index()//book list for admin
    {
        $books = $this->getAll();
        return view("books", compact("books"));
    }

    public function list()//book list for user
    {
        $user = Auth::user();
        $cart = Cart::with('cartdetails')->where("user_id", $user->id)->first();

        $books = $this->getAll();
        return view("book-list", compact("books", 'cart'));
    }

    public function AllCategories()
    {
        $categories = Categories::all();
        return $categories;
    }

    public function add()
    {
        $categories = $this->AllCategories();
        return view("add-book", compact("categories"));
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required',
            'author' => 'required|max:255',
            'category_id' => 'required'
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        $book = Books::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'author' => $request->input('author'),
            'category_id' => $request->input('category_id')
        ]);

        return redirect("books")->with("success", "Book added successfully..!");
    }

    public function edit($id)
    {
        $book = $this->getOne($id);
        $categories = $categories = $this->AllCategories();
        return view("add-book", compact("book", 'categories'));
    }

    public function update(Request $request, $id)
    {
        $book = $this->getOne($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $book->name = $request->input('name');
        $book->description = $request->input('description');
        $book->price = $request->input('price');
        $book->author = $request->input('author');
        $book->category_id = $request->input('category_id');

        $book->save();

        return redirect('books')->with('success', 'Book updated sucessfully..!');
    }

    public function delete($id)
    {
        $book = $this->getOne($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        $book->delete();

        return redirect("books")->with("success", "Deleted successfully..!");
    }

}
