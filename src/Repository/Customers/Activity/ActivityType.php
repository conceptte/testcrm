<?php

namespace Mtr\MiniCRM\Repository\Customers\Activity;

enum ActivityType: string
{
    case LOGIN = 'login';
    case EMAIL = 'email';
    case PURCHASE = 'purchase';
    case SUPPORT = 'support';

    /**
     * @return array
     */
    public static function toArray(): array
    {
        return array_map(fn (self $type) => $type->value, self::cases());
    }
}