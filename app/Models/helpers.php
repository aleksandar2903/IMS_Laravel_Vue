<?php

function format_money($money)
{
    if (session('language')) {
        $language = session('language');
    } elseif (config('app.locale')) {
        $language = config('app.locale');
    }

    $currrency = $language == 'sr' ? 'rsd ' : '€';

    if(!$money) {
        return add_currency(0.00, $currrency);
    }

    if(strpos($money, '-') !== false) {
        $formatted = explode('-', $money);
        return add_currency($formatted[1], $currrency);
    }

    return add_currency($money, $currrency);
}

function add_currency($money, $currrency) {
    if ($currrency == '€') {
        $money = $money / 117;
    }

    $money = number_format($money, 2);

    return $currrency."".$money;
}
