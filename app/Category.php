<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {

    public function items() {
        return $this->hasMany(Item::class);
    }

    public function addItem(Item $item) {
        return $this->items()->save($item);
    }

}
