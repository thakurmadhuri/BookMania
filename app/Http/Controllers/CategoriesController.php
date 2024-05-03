<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesResource;

class CategoriesController extends Controller
{
    public function index(){
        $categories = Categories::all();
        return view("categories",compact("categories"));
    }

    public function add(){
        return view("add-category");
    }
    
    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);
        $cat = Categories::create($request->all());
        return redirect("categories")->with("success","Category created successfully..!");
    }

    public function edit($id){
        
        $category = Categories::find($id);
        return view("edit-category",compact("category"));
    }

    public function update(Request $request, $id){
        
        $cat = Categories::find($id);
        $cat->update($request->all());
        return redirect("categories")->with("success","Updated successfully..!");
    }

    public function delete($id){
        Categories::where("id",$id)->delete();
        return redirect("categories")->with("success","Deleted successfully..!");
    }

    public function getAll(){
        $categories = Categories::all();
        return CategoriesResource::collection($categories);
    }
}
