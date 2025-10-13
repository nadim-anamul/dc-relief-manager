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

if (! function_exists('bn_to_en_number')) {
    function bn_to_en_number($number): string
    {
        $map = ['০'=>'0','১'=>'1','২'=>'2','৩'=>'3','৪'=>'4','৫'=>'5','৬'=>'6','৭'=>'7','৮'=>'8','৯'=>'9'];
        return strtr((string)$number, $map);
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

if (! function_exists('amount_format_bn')) {
    function amount_format_bn($amount, string $unit = ''): string
    {
        $formatted = number_format((float)$amount, 2);
        if (function_exists('locale_is_bn') && locale_is_bn()) {
            $bnFormatted = bn_number($formatted);
            return $unit ? $bnFormatted . ' ' . $unit : $bnFormatted;
        }
        return $unit ? $formatted . ' ' . $unit : $formatted;
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

if (! function_exists('bn_date')) {
    function bn_date($date, string $format = 'd M Y'): string
    {
        if (!locale_is_bn()) {
            return $date instanceof \DateTime ? $date->format($format) : date($format, strtotime($date));
        }

        // Convert to Carbon instance if needed
        if (!$date instanceof \Carbon\Carbon) {
            $date = \Carbon\Carbon::parse($date);
        }

        // Bengali month names
        $bnMonths = [
            1 => 'জানুয়ারি', 2 => 'ফেব্রুয়ারি', 3 => 'মার্চ', 4 => 'এপ্রিল',
            5 => 'মে', 6 => 'জুন', 7 => 'জুলাই', 8 => 'আগস্ট',
            9 => 'সেপ্টেম্বর', 10 => 'অক্টোবর', 11 => 'নভেম্বর', 12 => 'ডিসেম্বর'
        ];

        // Bengali day names
        $bnDays = [
            'Sunday' => 'রবিবার', 'Monday' => 'সোমবার', 'Tuesday' => 'মঙ্গলবার',
            'Wednesday' => 'বুধবার', 'Thursday' => 'বৃহস্পতিবার', 'Friday' => 'শুক্রবার',
            'Saturday' => 'শনিবার'
        ];

        $day = $date->day;
        $month = $date->month;
        $year = $date->year;
        $dayName = $date->format('l');

        // Convert numbers to Bengali
        $bnDay = bn_number($day);
        $bnYear = bn_number($year);

        // Format based on requested format
        switch ($format) {
            case 'd M Y':
                return $bnDay . ' ' . $bnMonths[$month] . ' ' . $bnYear;
            case 'M d, Y':
                return $bnMonths[$month] . ' ' . $bnDay . ', ' . $bnYear;
            case 'l, d M Y':
                return $bnDays[$dayName] . ', ' . $bnDay . ' ' . $bnMonths[$month] . ' ' . $bnYear;
            case 'd/m/Y':
                return $bnDay . '/' . bn_number($month) . '/' . $bnYear;
            case 'Y-m-d':
                return $bnYear . '-' . bn_number($month) . '-' . $bnDay;
            default:
                // Custom format - replace common patterns
                $formatted = $date->format($format);
                $formatted = str_replace($date->day, $bnDay, $formatted);
                $formatted = str_replace($date->month, bn_number($month), $formatted);
                $formatted = str_replace($date->year, $bnYear, $formatted);
                
                // Replace month names
                foreach ($bnMonths as $num => $bnMonth) {
                    $formatted = str_replace($date->format('F'), $bnMonth, $formatted);
                }
                
                // Replace day names
                foreach ($bnDays as $enDay => $bnDay) {
                    $formatted = str_replace($enDay, $bnDay, $formatted);
                }
                
                return $formatted;
        }
    }
}

if (! function_exists('bn_datetime')) {
    function bn_datetime($datetime, string $format = 'd M Y, h:i A'): string
    {
        if (!locale_is_bn()) {
            return $datetime instanceof \DateTime ? $datetime->format($format) : date($format, strtotime($datetime));
        }

        // Convert to Carbon instance if needed
        if (!$datetime instanceof \Carbon\Carbon) {
            $datetime = \Carbon\Carbon::parse($datetime);
        }

        // Bengali month names
        $bnMonths = [
            1 => 'জানুয়ারি', 2 => 'ফেব্রুয়ারি', 3 => 'মার্চ', 4 => 'এপ্রিল',
            5 => 'মে', 6 => 'জুন', 7 => 'জুলাই', 8 => 'আগস্ট',
            9 => 'সেপ্টেম্বর', 10 => 'অক্টোবর', 11 => 'নভেম্বর', 12 => 'ডিসেম্বর'
        ];

        $day = $datetime->day;
        $month = $datetime->month;
        $year = $datetime->year;
        $hour = $datetime->hour;
        $minute = $datetime->minute;
        $ampm = $datetime->format('A');

        // Convert numbers to Bengali
        $bnDay = bn_number($day);
        $bnYear = bn_number($year);
        $bnHour = bn_number($hour);
        $bnMinute = bn_number($minute);

        // Convert AM/PM to Bengali
        $bnAmpm = $ampm === 'AM' ? 'সকাল' : 'বিকাল';

        // Format based on requested format
        switch ($format) {
            case 'd M Y, h:i A':
                return $bnDay . ' ' . $bnMonths[$month] . ' ' . $bnYear . ', ' . $bnHour . ':' . $bnMinute . ' ' . $bnAmpm;
            case 'd/m/Y h:i A':
                return $bnDay . '/' . bn_number($month) . '/' . $bnYear . ' ' . $bnHour . ':' . $bnMinute . ' ' . $bnAmpm;
            default:
                // For custom formats, use the same logic as bn_date but include time
                $formatted = $datetime->format($format);
                $formatted = str_replace($datetime->day, $bnDay, $formatted);
                $formatted = str_replace($datetime->month, bn_number($month), $formatted);
                $formatted = str_replace($datetime->year, $bnYear, $formatted);
                $formatted = str_replace($datetime->hour, $bnHour, $formatted);
                $formatted = str_replace($datetime->minute, $bnMinute, $formatted);
                $formatted = str_replace('AM', 'সকাল', $formatted);
                $formatted = str_replace('PM', 'বিকাল', $formatted);
                
                // Replace month names
                foreach ($bnMonths as $num => $bnMonth) {
                    $formatted = str_replace($datetime->format('F'), $bnMonth, $formatted);
                }
                
                return $formatted;
        }
    }
}

if (! function_exists('relative_time_bn')) {
    function relative_time_bn($datetime): string
    {
        if (!locale_is_bn()) {
            return $datetime instanceof \Carbon\Carbon ? $datetime->diffForHumans() : \Carbon\Carbon::parse($datetime)->diffForHumans();
        }

        // Convert to Carbon instance if needed
        if (!$datetime instanceof \Carbon\Carbon) {
            $datetime = \Carbon\Carbon::parse($datetime);
        }

        $now = \Carbon\Carbon::now();
        $diff = $datetime->diff($now);

        // Bengali time units
        $units = [
            'year' => ['বছর', 'বছর'],
            'month' => ['মাস', 'মাস'],
            'week' => ['সপ্তাহ', 'সপ্তাহ'],
            'day' => ['দিন', 'দিন'],
            'hour' => ['ঘন্টা', 'ঘন্টা'],
            'minute' => ['মিনিট', 'মিনিট'],
            'second' => ['সেকেন্ড', 'সেকেন্ড']
        ];

        $ago = $datetime->isPast() ? 'আগে' : 'পরে';

        if ($diff->y > 0) {
            return bn_number($diff->y) . ' ' . $units['year'][0] . ' ' . $ago;
        } elseif ($diff->m > 0) {
            return bn_number($diff->m) . ' ' . $units['month'][0] . ' ' . $ago;
        } elseif ($diff->d > 0) {
            return bn_number($diff->d) . ' ' . $units['day'][0] . ' ' . $ago;
        } elseif ($diff->h > 0) {
            return bn_number($diff->h) . ' ' . $units['hour'][0] . ' ' . $ago;
        } elseif ($diff->i > 0) {
            return bn_number($diff->i) . ' ' . $units['minute'][0] . ' ' . $ago;
        } else {
            return bn_number($diff->s) . ' ' . $units['second'][0] . ' ' . $ago;
        }
    }
}


