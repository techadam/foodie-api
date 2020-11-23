<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Menu;

class Category extends Model
{
    protected $fillable = ['name'];

    public function menuItems() {
        return $this->hasMany(Menu::class);
    }
}
