<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function index()
    {
        $menu = Menu::with('category')->get();
        return response()->json(['data' => $menu]);
    }

    
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'name' => 'required|unique:menus',
            'price' => 'required',
            'image' => 'required',
            'isAvailable' => 'required',
        ]);

        if ($validator->fails()) {    
            return response()->json($validator->messages(), 400);
        }

        $menu = Menu::create($request->only([
            'category_id',
            'name',
            'price',
            'image',
            'isAvailable'
        ]));
        
        $menu->save();

        $response = Menu::with('category')->where('id', $menu->id)->get();
        return response()->json(['data' => $response]);

    }

    
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'name' => 'required|unique:menus,name',
            'price' => 'required',
            'image' => 'required',
            'isAvailable' => 'required',
        ]);

        if ($validator->fails()) {    
            return response()->json($validator->messages(), 400);
        }
        
        $menu = Menu::find($id);

        $menu->category_id = $request->category_id;
        $menu->name = $request->name;
        $menu->price = $request->price;
        $menu->image = $request->image;
        $menu->isAvailable = $request->isAvailable;

        $menu->save();
        
        return response()->json(['data' => $menu, 'message' => 'Menu item successfully updated']);
    }

    
    public function destroy($id)
    {
        $menu = Menu::find($id);
        $menu->delete();

        return response()->json('Menu Item successfully removed');
    }
}
