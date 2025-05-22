<?php

return [
    'guest_booking_code' => env('GUEST_BOOKING_CODE'),
    'year' => env('YEAR'),
    'theme' => env('THEME'),
    'guest_booking_limit' => env('GUEST_BOOKING_LIMIT'),
    'member_booking_limit' => env('MEMBER_BOOKING_LIMIT'),
    'payment_due_date' => env('PAYMENT_DUE_DATE'),
    'api_key' => env('API_KEY'),
    'awta_day' => env('AWTA_DAY'),
    'event_date' => env('EVENT_DATE'),
    'rebooking_deadline' => env('REBOOKING_DEADLINE'),
    'hybrid_registration_deadline' => env('HYBRID_REGISTRATION_DEADLINE'),
    'zoom_details' => [
        'link' => env('ZOOM_LINK'),
        'id' => env('ZOOM_ID'),
        'passcode' => env('ZOOM_PASSCODE'),
    ],
    'chart_color' => [
        'Day 1' => [
            'all' => '#fd7f6f',
            'member' => '#e60049',
            'guest' => '#ffa300'
        ],
        'Day 2' => [
            'all' => '#7eb0d5',
            'member' => '#0bb4ff',
            'guest' => '#dc0ab4'
        ],
        'Day 3' => [
            'all' => '#beb9db',
            'member' => '#50e991',
            'guest' => '#b3d4ff'
        ],
        'Day 4' => [
            'all' => '#fdcce5',
            'member' => '#e6d800',
            'guest' => '#00bfa0'
        ]
    ]
];
