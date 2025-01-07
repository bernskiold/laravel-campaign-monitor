<?php

namespace Bernskiold\LaravelCampaignMonitor\Data;

use Bernskiold\LaravelCampaignMonitor\Enum\CustomFieldType;
use Illuminate\Contracts\Support\Arrayable;

class ListCustomField
{
    public string $name;

    public CustomFieldType $type = CustomFieldType::Text;

    public bool $visibleInPreferenceCenter = false;

    public array $options = [];

    public static function make(): static
    {
        return new static;
    }

    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function type(CustomFieldType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function visibleInPreferenceCenter(bool $visible): static
    {
        $this->visibleInPreferenceCenter = $visible;

        return $this;
    }

    public function options(Arrayable|array $options): static
    {
        $this->options = $options instanceof Arrayable ? $options->toArray() : $options;

        return $this;
    }

    public function text(): static
    {
        return $this->type(CustomFieldType::Text);
    }

    public function number(): static
    {
        return $this->type(CustomFieldType::Number);
    }

    public function date(): static
    {
        return $this->type(CustomFieldType::Date);
    }

    public function multiSelectOne(): static
    {
        return $this->type(CustomFieldType::MultiSelectOne);
    }

    public function multiSelectMany(): static
    {
        return $this->type(CustomFieldType::MultiSelectMany);
    }

    public function country(): static
    {
        return $this->type(CustomFieldType::Country);
    }

    public function usState(): static
    {
        return $this->type(CustomFieldType::USState);
    }

    public function toApiRequest(): array
    {
        $data = [
            'FieldName' => $this->name,
            'DataType' => $this->type->value,
            'VisibleInPreferenceCenter' => $this->visibleInPreferenceCenter,
        ];

        if (in_array($this->type, CustomFieldType::supportsOptions())) {
            $data['Options'] = $this->options;
        }

        return $data;
    }
}
