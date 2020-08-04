<?php

namespace App\Http\Controllers;

use App\Category;

class CategoriesController extends Controller {

    public function index() {
        $data = Category::all();
        return response()->json($data);
    }

    public function show($id) {
        $item = Category::find($id);
        return response()->json($item);
    }

}
