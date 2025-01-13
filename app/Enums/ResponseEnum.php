<?php
namespace App\Enums;

enum ResponseEnum: string{
    const OK = 200;
    const NOTFOUND = 404;
    const UNAUTHORIZED = 401;
    const CREATED = 201;
    const ACCEPTED = 202;
    const NO_CONTENT = 204;
    const BADREQUEST = 400;
    const FORBIDDEN = 403;
}
