<?php

namespace components;

/**
 * Response class
 */
class Response
{
    /**
     * @param array $data
     * @param string $message
     * @return array
     */
    public static function success(string $message = 'success', array $data = []): array
    {
        return array_merge($data, ['result'=>'success', 'message'=>$message]);
    }

    /**
     * @param array $data
     * @param string $message
     * @return array
     */
    public static function error(string $message = 'error', array $data = [] ): array
    {
        return array_merge($data, ['result'=>'error', 'message'=>$message]);
    }
}