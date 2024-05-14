<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CategoriesResource;

class CategoriesController extends Controller
{
    public function getAll(){ 
        $categories = Category::all();
        return $categories;
    }

    public function getOne($id){ 
        $category = Category::find($id);
        return $category;
    }

    public function index(){
        $categories = $this->getAll();
        return view("categories",compact("categories"));
    }

    public function add(){
        return view("add-category");
    }
    
    public function store(Request $request)
    {
        $validated =  Validator::make($request->all(),[
            'name' => 'required|max:255',
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        $cat = Category::create([
            'name'=> $request->name,
        ]);
        return redirect("categories")->with("success","Category created successfully..!");
    }

    public function edit($id){
        
        $category =$this->getOne($id);
        if (!$category) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        return view("edit-category",compact("category"));
    }

    public function update(Request $request, $id){
        
        $cat = $this->getOne($id);
        if (!$cat) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $cat->name=$request->name;
        $cat->save();
        return redirect("categories")->with("success","Updated successfully..!");
    }

    public function delete($id){
        $cat = $this->getOne($id);
        if (!$cat) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        $cat->books()->delete();
        $cat->delete();
        return redirect("categories")->with("success","Deleted successfully..!");
    }

}
