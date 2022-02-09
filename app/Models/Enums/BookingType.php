<?php

namespace App\Models\Enums;

enum BookingType: string
{
    case booking = 'booking';
    case event = 'event';
    case exam = 'exam';
    case mentoring = 'mentoring';
}
