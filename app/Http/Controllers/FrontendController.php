<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\View\View;

class FrontendController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('index');
    }

    public function getBookings($order): array
    {
        $dbBookings = Booking::query()
            ->select(['id', 'cid', 'type', 'callsign', 'start', 'end'])
            ->where(function ($q) {
                $now = Carbon::now()->toDateTimeString();
                $today = Carbon::now()->toDateString();
                $_7_days = Carbon::now()->addDays(7)->endOfDay()->toDateTimeString();
                $q->whereDate('start', $today)
                    ->orWhereDate('end', $today)
                    ->orWhere(function($q2) use($_7_days, $now) {
                        $q2->where('end', '<', $_7_days)->where('start', '>', $now);
                    });
            })
            ->orderBy($order)
            ->get();

        $bookings = [];

        foreach ($dbBookings as $dbBooking) {
            $bookings[] = [
                'x' => format_callsign($dbBooking->callsign, $dbBooking->type),
                'y' => [
                    strtotime($dbBooking->start)*1000, strtotime($dbBooking->end)*1000
                ],
                'fillColor' => colour_from_progress(time(), strtotime($dbBooking->start), strtotime($dbBooking->end), $dbBooking->type),
            ];
        }

        return $bookings;
    }

    /**
     * @return View
     */
    public function apiDoc(): View
    {
        return view('api-doc');
    }
}
