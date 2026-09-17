<?php

namespace Bernskiold\LaravelCampaignMonitor\Exceptions;

use Bernskiold\LaravelCampaignMonitor\Enum\ApiErrorCode;
use CS_REST_Wrapper_Result;
use Exception;
use Throwable;

class CampaignMonitorException extends Exception
{
    public function __construct(
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
        public ?ApiErrorCode $apiErrorCode = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function missingApiKey(): self
    {
        return new static('No API key was provided for Campaign Monitor (CAMPAIGN_MONITOR_API_KEY).');
    }

    public static function fromResponse(CS_REST_Wrapper_Result $response): self
    {
        return new static(
            message: $response->response?->Message ?? 'Campaign Monitor responded with an error.',
            code: $response->http_status_code,
            apiErrorCode: ApiErrorCode::fromResponse($response),
        );
    }

    public function hasExceededRateLimit(): bool
    {
        return $this->code === 429;
    }

    public function isSubscriberNotInList(): bool
    {
        return $this->apiErrorCode === ApiErrorCode::SubscriberNotInList;
    }
}
