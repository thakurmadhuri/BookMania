<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BooksController extends Controller
{
    public function getAll()//book api
    {
        $books = Book::all();
        return $books;
    }

    public function getOne($id)//get one book
    {
        $book = Book::find($id);
        return $book;
    }

    public function index()//book list for admin
    {
        $books = Book::paginate(10);
        return view("books", compact("books"));
    }

    public function list(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::with('cartDetails')->where("user_id", $user->id)->first();

        $query = $request->input('q');
        $books = Book::where(function ($queryBuilder) use ($query) {
            $queryBuilder->where('name', 'LIKE', '%' . $query . '%')
                ->orWhere('description', 'LIKE', '%' . $query . '%');
        })->get();

        return view("book-list", compact("books", 'cart'));
    }

    public function AllCategories()
    {
        $categories = Category::all();
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
            'category_id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
        }

        $book = Book::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'author' => $request->input('author'),
            'category_id' => $request->input('category_id'),
            'image'=>'/images/'.$name
        ]);

        return redirect("books")->with("success", "Book added successfully..!");
    }

    public function edit($id)
    {
        $book = $this->getOne($id);
        $categories = $this->AllCategories();
        return view("add-book", compact("book", 'categories'));
    }

    public function update(Request $request, $id)
    {
        $book = $this->getOne($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);

            $book->image='/images/'.$name;
        }

        $book->name = $request->input('name');
        $book->description = $request->input('description');
        $book->price = $request->input('price');
        $book->author = $request->input('author');
        $book->category_id = $request->input('category_id');

        $book->save();

        return redirect('books')->with('success', 'Book updated successfully..!');
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
