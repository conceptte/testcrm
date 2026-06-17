<?php
namespace Mtr\MiniCRM\Repository\Customers;

enum CustomerStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    /**
     * @param string|null $status
     * 
     * @return bool|null
     */
    public static function isActive(?string $status = null): ?bool
    {
        return match ($status) {
            static::ACTIVE->value => true,
            static::INACTIVE->value => false,
            default => null,
        };
    }

    /**
     * @param string|null $status
     * 
     * @return bool
     */
    public static function isValid(?string $status = null): bool
    {
        return in_array($status, array_column(static::cases(), 'value'), true);
    }
}