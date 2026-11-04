<?php

namespace App\Enums;

/**
 * WM-04: DJ Punzalan · https://github.com/EE2506 · {{AUTHOR_EMAIL}}
 */
enum UserRole: string
{
    case User = 'user';
    case Admin = 'admin';
    case Superadmin = 'superadmin';
    case Developer = 'developer';
}
