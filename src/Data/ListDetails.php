<?php

namespace Bernskiold\LaravelCampaignMonitor\Data;

class ListDetails
{
    public string $title = '';

    public ?string $unsubscribePage = null;

    public string $unsubscribeSetting = 'OnlyThisList';

    public bool $confirmedOptIn = false;

    public ?string $confirmationSuccessPage = null;

    public static function make(): self
    {
        return new static;
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function unsubscribePage(string $unsubscribePage): self
    {
        $this->unsubscribePage = $unsubscribePage;

        return $this;
    }

    public function subscriptionConfirmationPage(string $confirmationSuccessPage): self
    {
        $this->confirmationSuccessPage = $confirmationSuccessPage;

        return $this;
    }

    public function unsubscribeFromAllLists(): self
    {
        $this->unsubscribeSetting = 'AllClientLists';

        return $this;
    }

    public function unsubscribeFromListOnly(): self
    {
        $this->unsubscribeSetting = 'OnlyThisList';

        return $this;
    }

    public function confirmedOptIn(bool $confirmedOptIn = true): self
    {
        $this->confirmedOptIn = $confirmedOptIn;

        return $this;
    }

    public function dontConfirmOptIn(): self
    {
        $this->confirmedOptIn = false;

        return $this;
    }

    public function toApiRequest(): array
    {
        $data = [
            'Title' => $this->title,
            'UnsubscribeSetting' => $this->unsubscribeSetting,
            'ConfirmedOptIn' => $this->confirmedOptIn,
        ];

        if (! is_null($this->unsubscribePage)) {
            $data['UnsubscribePage'] = $this->unsubscribePage;
        }

        if (! is_null($this->confirmationSuccessPage)) {
            $data['ConfirmationSuccessPage'] = $this->confirmationSuccessPage;
        }

        return $data;
    }
}
