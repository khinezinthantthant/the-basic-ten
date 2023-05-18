<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ItemApiController extends Controller
{
    public function __construct()
    {
        // $this->middleware('cat')->only(["store","delete","index","show"]);
        $this->middleware('cat')->except('index');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

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

        // return response()->json($items);

        return ItemResource::collection($items);

        // return response()->json([
        //     "a" => "aaa",
        //     "b" => "bbb",
        //     "myName" => "hein htet zan"
        // ],404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request;
        // $request->validate([
        //     "name" => "required",
        //     "price" => "required",
        //     "stock" => "required"
        // ]);

        $validator = Validator::make($request->all(),[
            "name" => "required",
            "price" => "required",
            "stock" => "required"
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(),422);
        }

        $item = Item::create([
            "name" => $request->name,
            "price" => $request->price,
            "stock" => $request->stock
        ]);

        // return response()->json($item);
        return new ItemResource($item);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Item::find($id);
        if(is_null($item)){
            return response()->json(["message" => "not found"],404);
        }
        // return response()->json($item);

        return new ItemResource($item);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // return $request;
        $validator = Validator::make($request->all(),[
            "name" => "required",
            "price" => "required",
            "stock" => "required"
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(),422);
        }

        $item = Item::find($id);
        if(is_null($item)){
            return response()->json(["message" => "not found"],404);
        }

        $item->update([
            "name" => $request->name,
            "price" => $request->price,
            "stock" => $request->stock
        ]);

        // return response()->json($item);
        return new ItemResource($item);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Item::findOrFail($id);
        if(is_null($item)){
            return response()->json(["message" => "not found"],404);
        }
        $item->delete();
        return response()->json([],204);
    }
}
