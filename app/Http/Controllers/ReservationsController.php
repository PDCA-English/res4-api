<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;

class ReservationsController extends Controller
{
    public function getReservationInfo(Request $request)
    {
        $item = Reservation::where('shop_id', $request->id)
            // ->where()
            ->get();
        return $item;
        return response()->json($item, 200);
    }

    public function confirmReservation(Request $request)
    {
        $item = Reservation::where('shop_id', $request->id)->get();
        return $item;
        $name = $item->name;
        $region = $item->region;
        $genre = $item->genre;
        $info = $item->info;
        $img_url = $item->img_url;
        $open = $item->open;
        $close = $item->close;
        $period = $item->period;
        $items = [
            "name" => $name,
            "region" => $region,
            "genre" => $genre,
            "info" => $info,
            "img_url" => $img_url,
            "open" => $open,
            "close" => $close,
            "period" => $period,
        ];
        return response()->json($items, 200);
    }

    public function getSlot(Request $request)
    {
        $shop_id = intval($request->shop_id);
        $user_id = intval($request->user_id);
        $startDate = date('Y-m-d', strtotime($request->startDate));
        $number = intval($request->number);
        // 時刻として認識させるために一度適当な日付（startDate）をくっつけてから時刻へ変換
        $open = date("H:i", strtotime($startDate ." ". $request->open));
        $close = date("H:i", strtotime($startDate ." ". $request->close));
        
        $period = intval($request->period);

        // 取得した予約希望時間と日付をまとめる
        $dateAndTime = date('Y-m-d H:i', strtotime(date('Y-m-d', strtotime($request->date)) ." ". $request->time));


        // 日付の配列に曜日をつけるため
        $week = [
            '日', //0
            '月', //1
            '火', //2
            '水', //3
            '木', //4
            '金', //5
            '土', //6
          ];

        // startDateから7日分の配列を作る
        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            array_push($dates, [date("Y-m-d",strtotime("+{$i} day", strtotime($startDate))),date("Y",strtotime("+{$i} day", strtotime($startDate))),date("m",strtotime("+{$i} day", strtotime($startDate))),date("d",strtotime("+{$i} day", strtotime($startDate))),$week[date('w',strtotime("+{$i} day", strtotime($startDate)))]]);
        }
        // 今日から30日間の配列を作る
        $datesOneMonthAhead = [];
        $today = date("Y-m-d");
        for ($j = 0; $j < 30; $j++) {
            array_push($datesOneMonthAhead, [date("Y-m-d",strtotime("+{$j} day", strtotime($today))),date("Y",strtotime("+{$j} day", strtotime($today))),date("m",strtotime("+{$j} day", strtotime($today))),date("d",strtotime("+{$j} day", strtotime($today))),$week[date('w',strtotime("+{$j} day", strtotime($startDate)))]]);
        }

        // 時間枠の配列を定義
        $times = [];
        $nextTime = "";
        for ($k = 0; $nextTime < $close; $k++) {
            $nextTime = date("H:i",strtotime("+ ".($k * 30)." minute", strtotime($open)));
            array_push($times, $nextTime);
        }

        // 日付ごとにforeach ※foreachの予定だったが、126行目からのような形にはまとまらず、ひとまずforで代用した
        $day_available_array =[];
        for($i = 0; count($dates) > $i; $i++) {
            array_push($day_available_array, $dates[$i]);
            for($j = 0; count($times) > $j; $j++){
                array_push($day_available_array[$i], [$times[$j]=> $this->judge($shop_id, $dateAndTime, $number, $period)]);
            }
        }

        // $day_available_array =[];
        // foreach($dates as $date){
        //     array_push($day_available_array, $date);
        //     foreach($times as $time){
        //         array_push($day_available_array, [$time=> $this->judge($shop_id, $dateAndTime, $number, $period)]);
        //     }
        // }

        $items = [
            "shop_id" => $shop_id,
            "user_id" => $user_id,
            "startDate" => $startDate,
            "number" => $number,
            "open" => $open,
            "close" => $close,
            "period" => $period,
            "dates" => $dates,
            "datesOneMonthAhead" => $datesOneMonthAhead,
            "times" => $times,
            "dateAndTime" => $dateAndTime,
            "day_available_array" => $day_available_array,
        ];
        return response()->json($items, 200);

        // [
        //     "2021-06-20"=>[
        //         '10:00'=>true, //その時間の空いている席が一個でもあればtrue
        //         '11:00'=>true,
        //         '12:00'=>false,
        //     ],
        //     "2021-06-21"=>[
        //         '10:00'=>true,
        //         '11:00'=>true,
        //         12:00=>false,
        //     ],
        //     "2021-06-21"=>[
        //         '10:00'=>true,
        //         '11:00'=>true,
        //         12:00=>false,
        //     ],
        //     ]
    }
    public function judge($shop_id, $dateAndTime, $number, $period)
    {
        $availableIds = $this->getAvailableTableId($shop_id, $dateAndTime, $number, $period);
        // 各テーブルに対して(以下の中で1個でもtrueが出てきたらtrue)
        return count($availableIds) > 0;
    }

    public function getAvailableTableId($shop_id, $dateAndTime, $number, $period)
    // public function getAvailableTableId(Request $request)
    {

        // $shop_id = intval($request->shop_id);
        // $number = intval($request->number);
        // $period = intval($request->period);
        // $dateAndTime = date('Y-m-d H:i', strtotime(date('Y-m-d', strtotime($request->date)) ." ". $request->time));

        // ①対象となるテーブル群を人数（number）とshop_idをもとに取得($tables)
        $tables = Table::where('capacity', '>=', $number)
            ->where('shop_id', $shop_id)
            ->get();

        // 空いている席のIDを取得する
        $availableTableIds = [];
        // 予約しようとしている時間幅を取得
        $reserving = [
            "start"=> $dateAndTime,
            "end"=> date('Y-m-d H:i',strtotime("+ ".($period)." minute", strtotime($dateAndTime))),
        ];


        // ②各テーブルに対して今までの予約を見る
        foreach($tables as $table){
            // $reservationsOnTheDayその日の予約群(reservations)を検索 (wheredateを使うとできる DDでworkしてるか確認)
            $reservationsOnTheDay = Table::where('shop_id', $shop_id)->get();
                $judge = true;
                // carbonを使ってうまく比較する
                foreach($reservationsOnTheDay as $reservation){
                    if($judge = $reserving["end"]<$reservation["date_time"]  || $reserving["start"]< date('Y-m-d H:i',strtotime("+ ".($period)." minute", strtotime($reservation["date_time"]))))
                    return false;{
                    }
                }
                array_push($availableTableIds, $table->id);
        }
        // $test = count($availableTableIds);

        // $items = [
        //     "shop_id" => $shop_id,
        //     "period" => $period,
        //     "number" => $number,
        //     "tables" => $tables,
        //     "reserving" => $reserving,
        //     "dateAndTime" => $dateAndTime,
        //     "availableTableIds" => $availableTableIds,
        //     "test" => $test, 
        // ];
        // return response()->json($items, 200);

    }

}
