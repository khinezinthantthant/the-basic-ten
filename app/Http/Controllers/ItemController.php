<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $items = Item::when(request()->has('keyword'),function($query){

        // })
        // ->paginate(7);

        // dd(request()->keyword)
        // dd(request()->has('keyword'));
        $items = Item::when(request()->has('keyword'),function($query){
            $keyword = request('keyword');
            $query->where("name","like","%". $keyword ."%");
            $query->orWhere("price","like","%" . $keyword . "%");
            $query->orWhere("stock","like","%" . $keyword . "%");
        })
        ->when(request()->has('name'),function($query){
            $sortType = request()->name ?? "asc";
            $query->orderBy('name',$sortType);
        })
        ->paginate(7)->withQueryString();
        return view('inventory.index',compact('items'));

        // $items = Item::get();
        // dd($items);

        // $items = Item::where("id",">",5)->dd(); //sql ကိုကြည့်
        // $items = Item::where("id",">",5)->get(); //sql ကိုကြည့်
        // $items = Item::where("id",">",5)->where("price",">",700)->dd();
        // $items = Item::where("id",">",5)->where("price",">",700)->get();
        // $items = Item::where("price",">",900)->orWhere("stock","<",10)->dd();
        // $items = Item::where("price",">",900)->orWhere("stock","<",10)->get();
        // $items = Item::whereIn("id",[5,10,17,25])->dd();
        // $items = Item::whereIn("id",[5,10,17,25])->get();
        // $items = Item::whereBetween("price",[700,900])->dd();
        // $items = Item::whereBetween("price",[700,900])->get();

        // $items = DB::table('items')->get();
        // $items = Item::when(true,function($query){
        //     $query->where("id",5);
        // })->get();

        // $items = Item::limit(5)
        // ->offset(5)
        // ->orderBy("id","desc")
        // ->get();

        // $items = Item::latest("id")->get();
        // $items = Item::where("id",10)->first();
        // $items = Item::where("id",">",40)->first();

        // dd($items);
        // return $items;

        // $item = Item::all();
        // return $item;
        // return $item->first();
        // return $item->last();
        // return $item->pluck("name","price");
        // return $item->sum("price");
        // return $item->keys();
        // return $item->values();
    //    return collect($item->first())->values();
    //    return collect($item->first())->values()->all();

        // dd($item->first());
        // dd($item->last());
        // dd($item->sum("price"));
        // dd($item->avg('price'));
        // dd($item->min('price'));
        // dd($item->max('price'));
        // $newItems = $item->map(fn($item) => [
        //     "name" => $item->name,
        //     "price" => $item->price
        // ]);

        // $newItems = $item->map(function($item){
        //     $item->price += 50;
        //     $item->stock -= 10;
        //     return $item;
        // });

        // dd($newItems);
        // dd($item->isEmpty());
        // dd($item->random());
        // dd($item->contains("name","Orange"));
        // dd($item->count());
        // dd($item->filter(fn($item) => $item->price > 500));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('inventory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
        $request->validate([
            'name' => "required|min:3|max:50|unique:items,name",
            'price' => "required|numeric|gte:50",
            "stock" => "required|numeric|gt:3"
        ]);

         // return $request;
         $item = new Item();
         $item->name = $request->name;
         $item->price = $request->price;
         $item->stock = $request->stock;
         $item->save();

         return redirect()->route('item.index')->with("status","Item Created Successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        return view('inventory.show',compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        return view('inventory.edit',compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        $item->name = $request->name;
        $item->price = $request->price;
        $item->stock = $request->stock;
        $item->update();
        return redirect()->route('item.index')->with("status","Item Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->back()->with('status',"Item Deleted Successfully");
    }
}
