<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Data;

use Carbon\CarbonInterface;

class SubscriberDetails
{
    public string $email = '';

    public string $name = '';

    public ?string $mobileNumber = null;

    public array $customFields = [];

    public bool $consentToTrack = false;

    public bool $consentToSendSms = false;

    public static function make(): static
    {
        return new static;
    }

    public function name(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function email(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function mobileNumber(?string $mobileNumber): self
    {
        $this->mobileNumber = $mobileNumber;

        return $this;
    }

    public function customField(string $key, CarbonInterface|string|int|null $value, bool $clear = false): self
    {
        $value = match (true) {
            $value instanceof CarbonInterface => $value->format('Y-m-d'),
            is_null($value) => '',
            default => $value,
        };

        $data = [
            'Key' => $key,
            'Value' => $value,
        ];

        if ($clear) {
            $data['Clear'] = true;
        }

        $this->customFields[] = $data;

        return $this;
    }

    public function consentsToTracking(?bool $consent = true): self
    {
        $this->consentToTrack = $consent;

        return $this;
    }

    public function consentsToSendSms(?bool $consent = true): self
    {
        $this->consentToSendSms = $consent;

        return $this;
    }

    public function toApiRequest(): array
    {
        $data = [
            'EmailAddress' => $this->email,
            'Name' => $this->name,
            'ConsentToTrack' => $this->consentToTrack ? 'Yes' : 'No',
            'ConsentToSendSms' => $this->consentToSendSms ? 'Yes' : 'No',
        ];

        if (! empty($this->customFields)) {
            $data['CustomFields'] = $this->customFields;
        }

        if ($this->mobileNumber) {
            $data['MobileNumber'] = $this->mobileNumber;
        }

        return $data;
    }
}
