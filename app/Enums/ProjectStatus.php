<?php

namespace App\Enums;

enum ProjectStatus: String
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case BLOCKED = 'blocked';
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';

    public static function toArray(): array
    {
        // Get status values from the ProjectStatus enum to show in the select input
        // https://stackoverflow.com/questions/73088100/laravel-php-8-1-how-to-convert-enum-cases-to-array-for-select-input
        $array = [];
        foreach (self::cases() as $case) {
            $array[] = [
                'value' => $case->value,
                'label' => str_replace('_', ' ', $case->name),
            ];
        }
        return $array;
    }
}
