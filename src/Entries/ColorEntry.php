<?php

declare(strict_types=1);

namespace Laravilt\Infolists\Entries;

class ColorEntry extends Entry
{
    protected bool $copyable = true;

    protected bool $showLabel = true;

    protected ?string $size = null;

    public function showLabel(bool $condition = true): static
    {
        $this->showLabel = $condition;

        return $this;
    }

    public function size(string $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function toLaraviltProps(): array
    {
        return array_merge(parent::toLaraviltProps(), [
            'showLabel' => $this->showLabel,
            'size' => $this->size,
        ]);
    }
}
