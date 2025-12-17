<?php
namespace App\Enums;

enum SalaryType: string
{
    case FIXED = 'fixed';
    case HOURLY = 'hourly';
    case COMMISSION = 'commission';

    public function label(): string
    {
        return match($this) {
            self::FIXED => 'Fixed salary',
            self::HOURLY => 'Hourly rate',
            self::COMMISSION => 'Commission',
        };
    }
}
