<?php

use Illuminate\Http\JsonResponse;

if (!function_exists('response_success')) {
    function response_success($data = []): JsonResponse
    {
        return response()->json($data);
    }
}

if (!function_exists('response_delete_success')) {
    function response_delete_success($data = []): JsonResponse
    {
        return response()->json($data, 204);
    }
}

if (!function_exists('response_created')) {
    function response_created($data = []): JsonResponse
    {
        return response()->json($data, 201);
    }
}

if (!function_exists('response_not_found')) {
    function response_not_found($data = []): JsonResponse
    {
        return response()->json($data, 404);
    }
}

if (!function_exists('response_validation_errors')) {
    function response_validation_errors($data = []): JsonResponse
    {
        return response()->json($data, 422);
    }
}

if (!function_exists('response_unauth')) {
    function response_unauth($data = []): JsonResponse
    {
        return response()->json($data, 401);
    }
}

if (!function_exists('adjust_brightness')) {
    function adjust_brightness($hex_code, $time): string
    {
        $adjust_float = $time == 'current' ? 0 : ($time == 'past' ? -0.3 : 0.3);
        $hexCode = ltrim($hex_code, '#');

        if (strlen($hexCode) == 3) {
            $hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
        }

        $hexCode = array_map('hexdec', str_split($hexCode, 2));

        foreach ($hexCode as & $color) {
            $adjustableLimit = $adjust_float < 0 ? $color : 255 - $color;
            $adjustAmount = ceil($adjustableLimit * $adjust_float);

            $color = str_pad(dechex($color + $adjustAmount), 2, '0', STR_PAD_LEFT);
        }

        return '#' . implode($hexCode);
    }
}

if (!function_exists('colour_from_progress')) {
    function colour_from_progress($now, $start, $end, $type): string
    {
        $booking = '#3C34FF';
        $exam = '#2ECB11';
        $training = '#ED2DC6';
        $event = '#EFB515';

        $time = $now < $start ? 'future' : ($now > $end ? 'past' : 'current');

        return match($type) {
            'exam' => adjust_brightness($exam, $time),
            'training' => adjust_brightness($training, $time),
            'event' => adjust_brightness($event, $time),
            default => adjust_brightness($booking, $time),
        };
    }
}

if (!function_exists('format_callsign')) {
    function format_callsign($callsign, $type = 'booking'): string
    {
        return match($type) {
            'event' => format_event_callsign($callsign),
            default => $callsign,
        };
    }
}

if (!function_exists('format_event_callsign')) {
    function format_event_callsign($callsign): string
    {
        $count = substr_count($callsign,'_');
        if($count > 1) {
            $explode = explode('_', $callsign);
            return $explode[0] . '_' . $explode[count($explode) - 1];
        }
        return $callsign;
    }
}
