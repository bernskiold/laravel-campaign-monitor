<?php

namespace Bernskiold\LaravelCampaignMonitor\Exceptions;

use CS_REST_Wrapper_Result;
use Exception;

class CampaignMonitorException extends Exception
{
    public static function missingApiKey(): self
    {
        return new static('No API key was provided for Campaign Monitor (CAMPAIGN_MONITOR_API_KEY).');
    }

    public static function fromResponse(CS_REST_Wrapper_Result $response): self
    {
        return new static(
            message: $response->response?->Message ?? 'Campaign Monitor responded with an error.',
            code: $response->http_status_code
        );
    }

    public function hasExceededRateLimit(): bool
    {
        return $this->code === 429;
    }
}
