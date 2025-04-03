<?php

namespace App\Constants;

class ResponseCodes
{
    // Success Codes
    const SUCCESS               = 200;
    const CREATED               = 201;

    // Error Codes
    const BAD_REQUEST           = 400;
    const UNAUTHORIZED          = 401;
    const NOT_FOUND             = 404;
    const INTERNAL_SERVER_ERROR = 500;
    const UNPROCESS_CONTENT     = 422;
    const FORBIDDEN             = 403;
    
}
