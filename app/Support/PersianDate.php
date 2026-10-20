<?php

namespace App\Support;

use DateTimeInterface;
use IntlDateFormatter;
use RuntimeException;

class PersianDate
{
    public static function format(?DateTimeInterface $date, string $pattern = 'yyyy/MM/dd'): string
    {
        if ($date === null) {
            return '—';
        }

        $formatter = new IntlDateFormatter(
            'fa_IR@calendar=persian',
            IntlDateFormatter::NONE,
            IntlDateFormatter::NONE,
            'Asia/Tehran',
            IntlDateFormatter::TRADITIONAL,
            $pattern,
        );

        $formattedDate = $formatter->format($date);
        if ($formattedDate === false) {
            throw new RuntimeException('Could not format the date using the Persian calendar.');
        }

        return $formattedDate;
    }
}
