<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Enums\BookingType;
use App\Rules\CallsignSearchFilter;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;

class BookingApiController extends Controller
{

    protected array $custom_messages = [
        'start.date_format' => 'The :attribute must be formatted as yyyy-mm-dd hh:mm:ss',
        'end.date_format' => 'The :attribute must be formatted as yyyy-mm-dd hh:mm:ss',
        'end.after' => 'The :attribute must be after the :date',
    ];

    protected array $custom_rules = [];
    protected array $custom_rules_update = [];
    protected array $custom_rules_filter = [];

    protected int $api_key_id;

    public function __construct(Request $request)
    {
        if (isset($request->user)) {
            $this->api_key_id = $request->user->id;
        }

        $this->custom_rules = [
            'callsign' => 'required|alpha_dash|ends_with:_DEL,_GND,_TWR,_APP,_DEP,_CTR,_FSS',
            'cid' => 'required|numeric',
            'start' => 'required|date_format:Y-m-d H:i:s',
            'end' => 'required|date_format:Y-m-d H:i:s|after:start',
            'division' => 'nullable|alpha|required_with:subdivision',
            'subdivision' => 'nullable|alpha',
            'type' => [new Enum(BookingType::class)],
        ];

        $this->custom_rules_update = [
            'callsign' => 'alpha_dash|ends_with:_DEL,_GND,_TWR,_APP,_DEP,_CTR,_FSS',
            'cid' => 'numeric',
            'start' => 'required_with:end|date_format:Y-m-d H:i:s',
            'end' => 'required_with:start|date_format:Y-m-d H:i:s|after:start',
            'division' => 'nullable|alpha|required_with:subdivision',
            'subdivision' => 'nullable|alpha',
            'type' => [new Enum(BookingType::class)],
        ];

        $this->custom_rules_filter = [
            'key_only' => 'boolean',
            'callsign' => [
                new CallsignSearchFilter
            ],
            'date' => 'date_format:Y-m-d',
            'division' => 'alpha|required_with:subdivision',
            'subdivision' => 'alpha',
            'type' => [
                new Enum(BookingType::class)
            ],
            'sort' => 'required_with:sort_dir|in:callsign,start,end',
            'sort_dir' => 'in:asc,desc',
        ];
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function index(Request $request): JsonResponse
    {
        $this->validate($request, $this->custom_rules_filter, $this->custom_messages);

        $query = Booking::query()
            ->select(['id', 'cid', 'type', 'callsign', 'start', 'end', 'division', 'subdivision']);

        if ($request->has('key_only') && (bool)$request->get('key_only') == true) {
            if (!isset($request->user)) {
                return response_unauth(['message' => 'Unable to Authenticate']);
            }
            $query = $query->where('api_key_id', $request->user->id);
        }

        if ($request->has('callsign')) {
            $arr_items = explode(',', $request->get('callsign'));
            $query = $query->where(function($q) use ($arr_items) {
                foreach ($arr_items as $arr_item) {
                    $q->orWhere('callsign', 'like', '%'.$arr_item.'%');
                }
            });
        }

        if ($request->has('division')) {
            $query = $query->where('division', $request->get('division'));

            if ($request->has('subdivision')) {
                $query = $query->where('subdivision', $request->get('subdivision'));
            }
        }

        if ($request->has('date')) {
            $query = $query->whereDate('start', $request->get('date'));
        } else {
            $query =  $query->where(function ($q) {
                $now = Carbon::now()->toDateTimeString();
                $today = Carbon::now()->toDateString();
                $q->whereDate('start', $today)
                    ->orWhereDate('end', $today)
                    ->orWhere('start', '>', $now)
                    ->orWhere('end', '>', $now);
            });
        }

        if($request->has('type')) {
            $query = $query->where('api_key_id', $request->user->id);
        }

        if($request->has('sort')) {
            $query->orderBy($request->get('sort'), $request->get('sort_dir', 'asc'));
        } else {
            $query->orderBy('start');
        }

        return response_success($query->get());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), $this->custom_rules, $this->custom_messages);

        $start = $request->post('start');
        $end = $request->post('end');
        $booking_exists = null;
        try {
            $booking_exists = Booking::query()
                ->where('callsign', $request->post('callsign'))
                ->where(function($q) use ($start, $end) {
                    $q->where('end', '>', $start)->where('start', '<', $end);
                })
                ->first();
        } catch (Exception) {}

        if($booking_exists) {
            $validator->errors()->add('callsign', 'Booking overlaps with an existing booking: '.$booking_exists->id);
        }

        if($validator->errors()->count()) {
            return response_validation_errors($validator->errors());
        }

        $booking = new Booking;
        $booking->api_key_id = $this->api_key_id;
        $booking->callsign = $request->post('callsign');
        $booking->cid = $request->post('cid');
        $booking->start = $request->post('start');
        $booking->end = $request->post('end');
        $booking->division = $request->post('division', $request->user->division);
        $booking->subdivision = $request->post('subdivision', $request->user->subdivision);
        $booking->type = $request->post('type', 'booking');
        $booking->save();

        return response_created($booking->only(['id', 'callsign', 'cid', 'type', 'start', 'end', 'division', 'subdivision']));
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $booking = Booking::query()->find($id);

        if (!$booking) {
            return response_not_found(['message' => 'Booking not found']);
        }

        return response_success($booking->only(['id', 'callsign', 'cid', 'type', 'start', 'end', 'division', 'subdivision']));
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), $this->custom_rules_update, $this->custom_messages);

        $start = $request->post('start');
        $end = $request->post('end');
        $booking_exists = null;
        try {
            $booking_exists = Booking::query()
                ->where('id', '!=', $id)
                ->where('callsign', $request->post('callsign'))
                ->where(function($q) use ($start, $end) {
                    $q->where('end', '>', $start)->where('start', '<', $end);
                })
                ->first();
        } catch (Exception) {}

        if($booking_exists) {
            $validator->errors()->add('callsign', 'Booking overlaps with an existing booking: '.$booking_exists->id);
        }

        if($validator->errors()->count()) {
            return response_validation_errors($validator->errors());
        }

        $booking = Booking::query()->find($id);

        if (!$booking) {
            return response_not_found();
        }

        if (isset($booking->api_key_id) && $booking->api_key_id != $this->api_key_id) {
            return response_unauth(['message' => 'Booking not owned by this API key']);
        }

        $booking->fill($request->only(['callsign', 'cid', 'start', 'end', 'type', 'division', 'subdivision']));
        $booking->save();

        return response_success($booking->only(['id', 'callsign', 'cid', 'type', 'start', 'end', 'division', 'subdivision']));
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $booking = Booking::query()->find($id);

        if (!$booking) {
            return response_not_found();
        }

        if (isset($booking->api_key_id) && $booking->api_key_id != $this->api_key_id) {
            return response_unauth(['message' => 'Booking not owned by this API key']);
        }

        $booking->delete();

        return response_delete_success();
    }

}
