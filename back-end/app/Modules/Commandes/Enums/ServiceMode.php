<?php

namespace App\Modules\Commandes\Enums;

enum ServiceMode: string
{
    case OnSite = 'SUR_PLACE';
    case Delivery = 'LIVRAISON';
}