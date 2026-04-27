<?php

use App\Models\User;

if (!function_exists('format_date')) {
    function format_date($date, $format = 'd-m-Y') {
        return \Carbon\Carbon::parse($date)->format($format);
    }
}


if (!function_exists('PerUser')) {
    function PerUser($permission) {
        return (auth()->check())?auth()->user()->can($permission):false;
    }
}

