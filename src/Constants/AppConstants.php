<?php

namespace Ensue\NicoSystem\Constants;

use Symfony\Component\HttpFoundation\Response;

class AppConstants
{
    /**
     * The general constant value for most of the success response
     *
     * @type string
     */
    public const SUCCESS_OK = 'ok';

    public const ERR_CODE_ZERO = 0;

    public const ERR_CODE_404 = 404;

    public const UNPROCESSABLE_ENTITY = 422;

    /**
     *
     * Note that this constant group is made as per \Illuminate\Contracts\Auth\PasswordBroker constant. We've only replaced dot with an underscore
     */
    public const SUCCESS_PASSWORD_RESET_SUCCESS = "passwords_reset";

    public const SUCCESS_PASSWORD_RESET_LINK_SENT = "passwords_sent";

    public const ERR_PASSWORD_INVALID_USER = "passwords_user";

    public const ERR_PASSWORD_INVALID_PASSWORD = "passwords_password";

    public const ERR_PASSWORDS_TOKEN = "passwords_token";

    public const ERR_PASSWORD_NOT_UPDATED = "err_password_not_updated";

    /**
     * Used typically whenever user try to submitForm with invalid username/password combination
     *
     * @type string
     */
    public const ERR_INVALID_CREDENTIAL = 'invalid_credentials';

    /**
     * Used typically whenever required value in the request is empty
     *
     * @type string
     */
    public const ERR_REQUIRED_FIELDS_EMPTY = 'required_fields_empty';

    public const ERR_EMAIL_DOESNT_EXIST_IN_DATABASE = 'email_doesnt_exist_in_system';

    public const ERR_MAIL_SERVER_AUTH_OR_CONF = "mail_server_auth_or_conf_error";

    public const ERR_FORM_VALIDATION = "form_validation_error";

    public const ERR_OLD_PASSWORD_MISMATCH = "old_password_mismatch";

    public const ERR_INTERNAL_SERVER_ERROR = "internal_server_error";

    public const ERR_UNAUTHORIZED = "unauthorized";

    public const ERR_FORBIDDEN = "forbidden";

    public const ERR_NOT_FOUND = "not_found";

    public const ERR_BAD_REQUEST = "err_bad_request";

    public const ERR_METHOD_NOT_ALLOWED = "method_not_allowed";

    public const ERR_EXPECTATION_FAILED = "expectation_failed";

    public const ERR_PRECONDITION_FAILED = "precondition_failed";

    public const ERR_CONFLICT = "conflict";

    public const ERR_USER_NOT_LOGGED_IN_OR_TOKEN_ABSENT = "user_not_logged_in_or_token_absent";

    public const ERR_INVALID_OAUTH_CLIENT = 'invalid_client';

    public const ERR_AUTH_TOKEN_ABSENT = 'auth_token_absent';

    public const ERR_INVALID_AUTH_TOKEN = 'invalid_auth_token';

    public const ERR_INVALID_CALLBACK_URL = 'invalid_callback_url';

    public const ERR_TOKEN_EXPIRED = 'auth_token_expired';

    public const ERR_TOO_MANY_LOGIN_ATTEMPT = 'too_many_bad_login_attempt';

    public const ERR_RESOURCE_IN_USE = 'err_resource_in_use';

    public const ERR_RESOURCE_NOT_FOUND = 'err_resource_not_found';

    public const ERR_AUTHENTICATION_ERROR = 'err_authentication_error';

    public const ERR_RUNTIME_ERROR = 'err_runtime_error';

    public const ERR_SUSPENDED_MODEL_NOT_EDITABLE = "err_resource_not_editable";

    public const ERR_USER_NOT_REGISTERED = 'user_not_registered';

    public const PASSWORDS_THROTTLED = 'passwords_throttled';

    public const RESOURCE_ALREADY_EXISTS = 'resource_already_exists';

    /**
     * @param $code
     * @return int|string
     */
    public static function getAppMsgCodeFromStatusCode($code): int|string
    {
        return match ($code) {
            400 => static::ERR_BAD_REQUEST,
            401 => static::ERR_UNAUTHORIZED,
            403 => static::ERR_FORBIDDEN,
            404 => static::ERR_NOT_FOUND,
            Response::HTTP_METHOD_NOT_ALLOWED => static::ERR_METHOD_NOT_ALLOWED,
            Response::HTTP_EXPECTATION_FAILED => static::ERR_EXPECTATION_FAILED,
            Response::HTTP_PRECONDITION_FAILED => static::ERR_PRECONDITION_FAILED,
            Response::HTTP_INTERNAL_SERVER_ERROR => static::ERR_INTERNAL_SERVER_ERROR,
            default => static::ERR_CODE_ZERO,
        };
    }
}
