<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Student = 'student';
    case Industry = 'industry';
    case Researcher = 'researcher';
}
