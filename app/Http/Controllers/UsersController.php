<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UsersController extends Controller
{
    public function get(Request $request)
    {
        if ($request->has('email')) {
            $items = DB::table('users')->where('email', $request->email)->get();
            return response()->json([
                'message' => 'User got successfully',
                'data' => $items
            ], 200);
        } else {
            return response()->json(['status' => 'not found'], 404);
        }
    }

    public function getshops()
    {
        $items = User::where('type', 2)->get();
        return response()->json([
            'message' => 'Shops got successfully',
            'data' => $items
        ], 200);
    }

    public function getShopInfo(Request $request)
    {
        // $item = User::where('id', $request->id)->first();
        // return $item;
        // $name = $item->name;
        // $region = $item->region;
        // $genre = $item->genre;
        // $info = $item->info;
        // $img_url = $item->img_url;
        // $open = $item->open;
        // $close = $item->close;
        // $period = $item->period;
        // $items = [
        //     "name" => $name,
        //     "region" => $region,
        //     "genre" => $genre,
        //     "info" => $info,
        //     "img_url" => $img_url,
        //     "open" => $open,
        //     "close" => $close,
        //     "period" => $period,
        // ];
        // return response()->json($items, 200);
        
        $shop_id = $request->id;
        $shop = User::where('id', $shop_id)->first();
        $table = Table::where('shop_id', $shop_id)->get();
        $reservation = Reservation::where('shop_id', $shop_id)->get();
        $favorite = Favorite::where('shop_id', $shop_id)->get();
        $shops = [
            "shop" => $shop,
            "table" => $table,
            "reservation" => $reservation,
            "favorite" => $favorite,
        ];
        return response()->json($shops, 200);
    }

    public function put(Request $request)
    {
        $param = [
            'profile' => $request->profile,
            'email' => $request->email
        ];
        DB::table('users')->where('email', $request->email)->update($param);
        return response()->json([
            'message' => 'User updated successfully',
            'data' => $param
        ], 200);
    }
}
