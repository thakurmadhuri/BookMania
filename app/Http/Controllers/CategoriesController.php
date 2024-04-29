<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;

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
}
