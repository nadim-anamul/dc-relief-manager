<?php

if (! function_exists('bn_number')) {
    function bn_number($number): string
    {
        if (function_exists('locale_is_bn') && locale_is_bn()) {
            $map = ['0'=>'০','1'=>'১','2'=>'২','3'=>'৩','4'=>'৪','5'=>'৫','6'=>'৬','7'=>'৭','8'=>'৮','9'=>'৯'];
            return strtr((string)$number, $map);
        }
        return (string)$number;
    }
}

if (! function_exists('money_format_bn')) {
    function money_format_bn($amount, string $currencySymbol = '৳'): string
    {
        $formatted = number_format((float)$amount, 2);
        if (function_exists('locale_is_bn') && locale_is_bn()) {
            return $currencySymbol . bn_number($formatted);
        }
        return '$' . $formatted;
    }
}

if (! function_exists('locale_is_bn')) {
    function locale_is_bn(): bool
    {
        return app()->isLocale('bn');
    }
}

if (! function_exists('localized_value')) {
    function localized_value($en = null, $bn = null)
    {
        if (locale_is_bn()) {
            return $bn ?? $en;
        }
        return $en ?? $bn;
    }
}

if (! function_exists('localized_attr')) {
    function localized_attr($model, string $attribute): mixed
    {
        $en = $model?->{$attribute} ?? null;
        $bnAttribute = $attribute . '_bn';
        $bn = $model?->{$bnAttribute} ?? null;
        return localized_value($en, $bn);
    }
}


