<?php

declare(strict_types=1);

namespace Laravilt\Infolists\Entries;

class KeyValueEntry extends Entry
{
    protected ?string $keyLabel = 'Key';

    protected ?string $valueLabel = 'Value';

    protected bool $copyableKeys = false;

    protected bool $copyableValues = false;

    public function keyLabel(string $label): static
    {
        $this->keyLabel = $label;

        return $this;
    }

    public function valueLabel(string $label): static
    {
        $this->valueLabel = $label;

        return $this;
    }

    public function copyableKeys(bool $condition = true): static
    {
        $this->copyableKeys = $condition;

        return $this;
    }

    public function copyableValues(bool $condition = true): static
    {
        $this->copyableValues = $condition;

        return $this;
    }

    public function toLaraviltProps(): array
    {
        return array_merge(parent::toLaraviltProps(), [
            'keyLabel' => $this->keyLabel,
            'valueLabel' => $this->valueLabel,
            'copyableKeys' => $this->copyableKeys,
            'copyableValues' => $this->copyableValues,
        ]);
    }
}
