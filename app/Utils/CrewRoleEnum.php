<?php

namespace App\Utils;

enum CrewRoleEnum: int
{
    case OPERATOR_ROLE = 0;
    case DRIVER_ROLE = 1;
    case ASSOCIATE_DRIVER_ROLE = 2;
}
