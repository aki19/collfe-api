<?php

namespace App\Http\Controllers;

use App\Item;
use Laravel\Lumen\Http\Request;

class ItemsController extends Controller {

    public function index() {
        $data = Item::all();
        return response()->json($data);
    }

    public function create(Request $request) {
        $item              = new Item;
        $item->code        = $request->code;
        $item->title       = $request->title;
        $item->description = $request->description;

        $item->save();
        return response()->json($item);
    }

    public function show($id) {
        $item = Item::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id) {
        $item = Item::findOrFail($id);

        $item->code        = $request->input('code');
        $item->title       = $request->input('title');
        $item->description = $request->input('description');
        $item->save();
        return response()->json($item);
    }

    public function destroy($id) {
        $item = Item::findOrFail($id);
        $item->delete();
        return response()->json('item removed successfully');
    }

}
