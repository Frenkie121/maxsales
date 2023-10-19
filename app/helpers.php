<?php

use Carbon\Carbon;

if (! function_exists('formatedLocaleDate')) {
    function formatedLocaleDate(?string $date)
    {
        $locale = app()->getLocale();
        Carbon::setLocale($locale);
        $format = $locale === 'en' ? 'F d, Y' : 'd M Y';

        return $date ? Carbon::parse($date)->translatedFormat($format) : null;
    }
}

if (! function_exists('greeting')) {
    function greeting()
    {
        $hour = date('H');

        return ($hour > 17) ? trans('Good evening ') : (($hour > 12 && $hour <= 18) ? trans('Good afternoon ') : trans('Good morning '));
    }
}

if (! function_exists('formatMoney')) {
    function formatMoney(int $amount)
    {
        if (app()->getLocale() === 'en') {
            return number_format($amount);
        } else {
            return number_format($amount, 0, ',', ' ');
        }
    }
}

if (! function_exists('generateProductCode')) {
    function generateProductCode(string $name)
    {
        $array_words = explode(' ', $name);
        $count = count($array_words);
        $code = '';
        if ($count === 1) {
            $code = substr($name, 0, 5);
        } elseif ($count === 2) {
            $code = substr($array_words[0], 0, 3) . '_' . substr($array_words[1], 0, 3);
        } else {
            $code = substr($array_words[0], 0, 3) . '_' . substr($array_words[1], 0, 3) . '_' . substr($array_words[$count - 1], 0, 3);
        }
        
        return strtoupper($code);
    }
}