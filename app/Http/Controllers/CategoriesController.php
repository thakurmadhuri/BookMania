<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CategoriesResource;

class CategoriesController extends Controller
{
    public function getAll()
    {
        $categories = Category::all();
        return $categories;
    }

    public function getOne($id)
    {
        $category = Category::find($id);
        return $category;
    }

    public function index()
    {
        $categories = $this->getAll();
        return view("categories", compact("categories"));
    }

    public function add()
    {
        return view("add-category");
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = Validator::make($request->all(), [
                'name' => 'required|max:255|unique',
            ]);

            if ($validated->fails()) {
                return redirect()->back()->withErrors($validated)->withInput();
            }

            $cat = Category::create([
                'name' => $request->name,
            ]);

            activity('Category Activity')->event('Created')
                ->withProperties([
                    'name' => $request->name,
                ])
                ->performedOn($cat)
                ->causedBy(auth()->user())
                ->log('New Category Created');
                
            DB::commit();
            return redirect("categories")->with("success", "Category created successfully..!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect("categories")->with("error", $e->getMessage());
        }
    }

    public function edit($id)
    {
        $category = $this->getOne($id);
        if (!$category) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        return view("edit-category", compact("category"));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $cat = $this->getOne($id);
            if (!$cat) {
                return redirect("categories")->with("error", "Category not found..!");
            }

            $cat->name = $request->name;
            $cat->save();

            activity('Category Activity')->event('Update')
                ->withProperties([
                    'name' => $request->name,
                ])
                ->performedOn($cat)
                ->causedBy(auth()->user())
                ->log('Category Updated');

            DB::commit();
            return redirect("categories")->with("success", "Updated successfully..!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect("categories")->with("error", $e->getMessage());
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $cat = $this->getOne($id);
            if (!$cat) {
                return redirect("categories")->with("error", "Category not found..!");
            }

            $cat->delete();
            DB::commit();
            return redirect("categories")->with("success", "Deleted successfully..!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect("categories")->with("error", $e->getMessage());
        }
    }

}
