<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class FavoritesController extends Controller
{
    public function get(Request $request)
    {
        // DD("user_id",$request->user_id);
        $item = Favorite::where('shop_id', $request->shop_id)->where('user_id', $request->user_id)->first();
        if (is_null($item)) {
            return response()->json(['data' => false], 200);
        } else {
            return response()->json([
                'message' => 'Favorite got successfully',
                'data' => true,
            ], 200);
        }
    }



    public function post(Request $request)
    {
        $now = Carbon::now();
        $param = [
            "user_id" => $request->user_id,
            "shop_id" => $request->shop_id,
            "created_at" => $now,
            "updated_at" => $now
        ];
        DB::table('favorites')->insert($param);
        return response()->json([
            'message' => 'Favorite created successfully',
            'data' => $param
        ], 200);
    }
    public function delete(Request $request)
    {
        DB::table('favorites')->where('user_id', $request->user_id)->where('shop_id', $request->shop_id)->delete();
        return response()->json([
            'message' => 'Favorite deleted successfully',
        ], 200);
    }

    

}
