<?php

class RequestHelper {
    public static function isApiRequest() {
        return (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
            || (isset($_GET['api']) && $_GET['api'] === 'true');
    }

    public static function getInput() {
        return self::isApiRequest() ? json_decode(file_get_contents('php://input'), true) : $_POST;
    }
}

class ResponseHelper {
    public static function jsonResponse($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}

class ValidationHelper {
    public static function validateRequiredFields($fields, $input) {
        foreach ($fields as $field) {
            if (empty($input[$field])) {
                return false;
            }
        }
        return true;
    }
}
