<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model {

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function scopeOfCategory($query, $categoryId) {
        return $query->where("category_id", $categoryId);
    }

}
