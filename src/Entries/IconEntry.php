<?php

declare(strict_types=1);

namespace Laravilt\Infolists\Entries;

class IconEntry extends Entry
{
    protected ?string $size = null;

    protected bool $circular = false;

    protected ?string $iconColor = null;

    public function size(string $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function circular(bool $condition = true): static
    {
        $this->circular = $condition;

        return $this;
    }

    public function iconColor(string $color): static
    {
        $this->iconColor = $color;

        return $this;
    }

    public function boolean(string $trueIcon = 'check-circle', string $falseIcon = 'x-circle'): static
    {
        $this->formatStateUsing(function ($state) use ($trueIcon, $falseIcon) {
            return $state ? $trueIcon : $falseIcon;
        });

        return $this;
    }

    public function toLaraviltProps(): array
    {
        $props = parent::toLaraviltProps();

        // Format the state (e.g., convert boolean to icon name)
        if ($this->formatStateUsing) {
            $props['state'] = $this->formatState($this->state);
        }

        return array_merge($props, [
            'size' => $this->size,
            'circular' => $this->circular,
            'iconColor' => $this->iconColor,
        ]);
    }
}
