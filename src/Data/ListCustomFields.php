<?php

namespace Bernskiold\LaravelCampaignMonitor\Data;

class ListCustomFields
{
    /**
     * @var ListCustomField[]
     */
    public array $fields = [];

    /**
     * @param  ListCustomField[]  $fields
     */
    public function __construct(array $fields = [])
    {
        $this->fields = $fields;
    }

    public static function make(array $fields = []): self
    {
        return new static($fields);
    }

    public function add(ListCustomField $field): self
    {
        $this->fields[] = $field;

        return $this;
    }

    public function all(): array
    {
        return $this->fields;
    }
}
