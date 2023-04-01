<?php

declare(strict_types=1);

namespace App\Entity;

enum Level : string
{
    case junior = 'JUNIOR';
    case operationnel ='OPERATIONNEL';
    case confirme = 'CONFIRME';
    case senior = 'SENIOR';
    case expert = 'EXPERT';
    
}
