<?php

namespace App;

enum GenderType: int
{
    case Female = 0;
    case Male = 1;
    case Both = 2;

    public function label(): string
    {
        return match ($this) {
            self::Female => 'Female',
            self::Male => 'Male',
            self::Both => 'Both',
        };
    }
}
