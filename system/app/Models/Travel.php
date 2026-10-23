<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Travel extends Model
{
    use HasFactory;

    protected $table = "travels";

    protected $fillable = [
        'travel_id',
        'departure',
        'arrival',
        'travel_date',
        'line_id',
        'route_name',
        'sub_routes',
        'is_active',
        'salesable_count',
    ];

    protected $casts = [
        'travel_date' => 'datetime',
        'is_active' => 'boolean',
        'salesable_count' => 'integer',
    ];


    public static function getTickets()
    {
        return DB::table('LUKSYONCA.TICKET as T')
            ->leftJoin('LUKSYONCA.TICKETWEB as TW', 'T.ID', '=', 'TW.TICKETID')
            ->leftJoin('passenger_information as PI', 'T.ID', '=', 'PI.pnr_number')
            ->selectRaw("
                CONCAT(T.PASSENGERNAME, ' ', IFNULL(T.PASSENGERSURNAME, '')) AS PASSENGERNAME,
                T.ID AS TICKETID,
                (CASE WHEN T.GENDER='E' THEN 'man' ELSE 'woman' END) AS GENDER,
                T.NATIONALITY,
                T.TCNUMBER,
                T.TELEPHONE,
                T.STATE,
                PI.pnr_number,
                CASE
                    WHEN T.STATE = 'I' THEN 'cancelled'
                    WHEN T.STATE = 'S' THEN 'sold'
                    WHEN T.STATE = 'R' THEN 'sold'
                    WHEN T.LOCATIONDATE < CURRENT_DATE THEN 'completed'
                    ELSE 'unknown'
                END AS STATE_LABEL,
                COALESCE(NULLIF(T.PRICE, 0), PI.price) AS PRICE,
                (CASE WHEN TRANSUSER='luksyoncaood' THEN '€' ELSE '₺' END) CURRENCY,  
                TW.CARDPRICE,
                T.SEAT,
                T.SAVEDATE, T.LOCATIONDATE, T.RETDATE,
                DATE(T.LOCATIONDATE) AS DEPARTUREDATE, DATE_FORMAT(T.LOCATIONDATE, '%H:%i') AS DEPARTURETIME,
                (SELECT NAME FROM LUKSYONCA.LOCATION WHERE ID = T.DEPARTURE) AS DEPARTURENAME,
                (SELECT NAME FROM LUKSYONCA.LOCATION WHERE ID = T.ARRIVAL) AS ARRIVALNAME
            ")
            // ->whereIn("T.STATE",["S","I"]);
            ;
    }
}
