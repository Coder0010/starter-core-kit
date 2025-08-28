<?php

return [
    'missing_api_headers' => 'Required API request headers are missing or invalid. Please include Accept and Content-Type with the value application/json.',
    'internal_server_error' => 'An unexpected error occurred. Please try again later.',
    'model_not_found' => 'The requested Model not found.',
    'route_not_found' => 'The requested route was not found.',
    'method_not_allowed' => 'The HTTP method used is not allowed for this route.',
    'validation_error' => 'There was a validation error with the provided data.',
    'unauthorized' => 'You are not authorized to perform this action.',
    'unauthenticated' => 'Authentication is required to perform this action.',
    'too_many_requests' => 'Too many requests. Please slow down and try again later.',
    'relation_not_found' => 'The requested relation could not be found.',
    'type_error' => 'The provided data type is invalid.',
    'value_error' => 'The provided value is invalid.',
    'maintenance_mode' => 'The service is temporarily unavailable due to maintenance. Please try again later.',
    'fatal_error' => 'A fatal error occurred in the application code. Please check the implementation.',
    'pdo_exception' => 'A database error occurred (PDO Exception).',
];
