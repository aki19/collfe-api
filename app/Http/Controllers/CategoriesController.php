<?php

namespace App\Http\Controllers;

use App\Category;

class CategoriesController extends Controller {

    public function index() {
        $data = Category::all();
        return response()->json($data);
    }

    public function show($id) {
        $item = Category::findOrFail($id);
        return response()->json($item);
    }

}
