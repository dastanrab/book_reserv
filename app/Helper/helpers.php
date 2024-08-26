<?php
use Symfony\Component\HttpFoundation\Response;

if(!function_exists('apiResponseStandard')) {
    function apiResponseStandard(mixed $data = null, string $message = "", int $statusCode = Response::HTTP_OK, array $errors = []): \Illuminate\Http\JsonResponse
    {
        $response = [];
        $response['message'] = $message;
        $response['errors'] = $errors;
        $response['data'] = $data;
        $response['status'] = $statusCode;
        $statusCode = array_key_exists($statusCode, Response::$statusTexts) ? $statusCode : Response::HTTP_OK;
        return response()->json($response, $statusCode);
    }
}
if (!function_exists('get_slug_string')) {
    function get_slug_string($string = null)
    {
        return str_replace(" ", "-", $string);
    }
}
