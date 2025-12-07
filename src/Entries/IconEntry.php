<?php

declare(strict_types=1);

namespace Laravilt\Infolists\Entries;

class IconEntry extends Entry
{
    protected ?string $size = null;

    protected bool $circular = false;

    protected ?string $iconColor = null;

    protected bool $isBoolean = false;

    protected ?string $booleanTrueIcon = 'check-circle';

    protected ?string $booleanFalseIcon = 'x-circle';

    protected ?string $booleanTrueColor = 'success';

    protected ?string $booleanFalseColor = 'danger';

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

    /**
     * Set this entry as a boolean display.
     */
    public function boolean(?string $trueIcon = null, ?string $falseIcon = null): static
    {
        $this->isBoolean = true;

        if ($trueIcon !== null) {
            $this->booleanTrueIcon = $trueIcon;
        }

        if ($falseIcon !== null) {
            $this->booleanFalseIcon = $falseIcon;
        }

        return $this;
    }

    /**
     * Set the icon for true state (FilamentPHP v4 compatibility).
     */
    public function trueIcon(string $icon): static
    {
        $this->booleanTrueIcon = $icon;

        return $this;
    }

    /**
     * Set the icon for false state (FilamentPHP v4 compatibility).
     */
    public function falseIcon(string $icon): static
    {
        $this->booleanFalseIcon = $icon;

        return $this;
    }

    /**
     * Set the color for true state (FilamentPHP v4 compatibility).
     */
    public function trueColor(string $color): static
    {
        $this->booleanTrueColor = $color;

        return $this;
    }

    /**
     * Set the color for false state (FilamentPHP v4 compatibility).
     */
    public function falseColor(string $color): static
    {
        $this->booleanFalseColor = $color;

        return $this;
    }

    public function toLaraviltProps(): array
    {
        $props = parent::toLaraviltProps();

        // Handle boolean display
        if ($this->isBoolean) {
            $state = $this->state;
            $props['state'] = $state ? $this->booleanTrueIcon : $this->booleanFalseIcon;
            $props['iconColor'] = $state ? $this->booleanTrueColor : $this->booleanFalseColor;
        } elseif ($this->formatStateUsing) {
            // Format the state (e.g., convert boolean to icon name)
            $props['state'] = $this->formatState($this->state);
        }

        return array_merge($props, [
            'size' => $this->size,
            'circular' => $this->circular,
            'iconColor' => $props['iconColor'] ?? $this->iconColor,
            'isBoolean' => $this->isBoolean,
            'trueIcon' => $this->booleanTrueIcon,
            'falseIcon' => $this->booleanFalseIcon,
            'trueColor' => $this->booleanTrueColor,
            'falseColor' => $this->booleanFalseColor,
        ]);
    }
}
