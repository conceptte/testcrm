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
}