<?php

namespace Bernskiold\LaravelCampaignMonitor\Enum;

use CS_REST_Wrapper_Result;

/**
 * The error codes returned by the Campaign Monitor API.
 *
 * These are returned in the body of an unsuccessful response,
 * and are more specific than the HTTP status code.
 *
 * @see https://www.campaignmonitor.com/api/v3-3/getting-started/
 */
enum ApiErrorCode: int
{
    case InvalidEmailAddress = 1;
    case InvalidApiKey = 100;
    case InvalidClientId = 102;
    case InvalidOAuthToken = 120;
    case ExpiredOAuthToken = 121;
    case RevokedOAuthToken = 122;
    case AlreadySubscribed = 201;
    case SubscriberNotInList = 203;
    case InvalidNewEmailAddress = 211;
    case MissingConsentToTrack = 214;
    case InvalidConsentToTrack = 215;
    case InvalidMobileNumber = 220;
    case InvalidEmailAddressAndMobileNumber = 221;
    case ListTitleNotUnique = 250;
    case NotAllowedForNonAgencyCustomer = 403;
    case ResourceNotFound = 404;
    case RateLimitExceeded = 429;
    case ServerError = 500;

    public static function fromResponse(CS_REST_Wrapper_Result $response): ?self
    {
        if (! is_object($response->response)) {
            return null;
        }

        $code = $response->response->Code ?? null;

        if (! is_numeric($code)) {
            return null;
        }

        return self::tryFrom((int) $code);
    }
}
