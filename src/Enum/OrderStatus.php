<?php

namespace App\Enum;

enum OrderStatus: string
{
    case paid = "paid";
    case unpaid = "unpaid";
}
