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
                'x' => $dbBooking->callsign,
                'y' => [
                    strtotime($dbBooking->start)*1000, strtotime($dbBooking->end)*1000
                ],
                'fillColor' => $this->colourFromProgress(time(), strtotime($dbBooking->start), strtotime($dbBooking->end), $dbBooking->type),
            ];
        }

        return $bookings;
    }

    protected function colourFromProgress($now, $start, $end, $type): string
    {
        // future
        if ($now < $start) {
            if($type == 'booking') {
                return '#8981FFCC';
            } else if ($type == 'exam') {
                return '#7BFF5ECC';
            } else if ($type == 'training') {
                return '#FF7AFFCC';
            } else if ($type == 'event') {
                return '#FFFF62CC';
            } else {
                return '#8981FFCC';
            }
        }
        // past
        if ($now > $end) {
            if($type == 'booking') {
                return '#0000B3CC';
            } else if ($type == 'exam') {
                return '#007F00CC';
            } else if ($type == 'training') {
                return '#A3007ACC';
            } else if ($type == 'event') {
                return '#A36900CC';
            } else {
                return '#0000B3CC';
            }
        }
        if($type == 'booking') {
            return '#3C34FFCC';
        } else if ($type == 'exam') {
            return '#2ECB11CC';
        } else if ($type == 'training') {
            return '#ED2DC6CC';
        } else if ($type == 'event') {
            return '#EFB515CC';
        } else {
            return '#3C34FFCC';
        }
    }

    /**
     * @return View
     */
    public function apiDoc(): View
    {
        return view('api-doc');
    }
}
