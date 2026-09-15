<?php

declare(strict_types=1);

namespace Laravilt\Infolists\Entries;

class BadgeEntry extends Entry
{
    /** @var array<string, string> */
    protected array $colors = [];

    /** @var array<string, string> */
    protected array $icons = [];

    /**
     * @param  array<string, string>  $colors
     */
    public function colors(array $colors): static
    {
        $this->colors = $colors;

        return $this;
    }

    /**
     * @param  array<string, string>  $icons
     */
    public function icons(array $icons): static
    {
        $this->icons = $icons;

        return $this;
    }

    /**
     * Array key for a state in the colors / icons maps, or null when the state cannot be a key.
     * Booleans map to 1 / 0, like PHP casts them as array keys.
     */
    protected function getStateKey(mixed $state): string|int|null
    {
        if (is_bool($state)) {
            return (int) $state;
        }

        if (is_int($state) || is_string($state)) {
            return $state;
        }

        return null;
    }

    public function bool(string $trueIcon = 'heroicon-o-check-circle', string $falseIcon = 'heroicon-o-x-circle'): static
    {
        $this->formatStateUsing(function ($state) use ($trueIcon, $falseIcon) {
            return $state ? $trueIcon : $falseIcon;
        });

        // Set default colors for true/false
        $this->colors = [
            $trueIcon => 'success',
            $falseIcon => 'danger',
        ];

        // Set icons to render icons instead of text
        $this->icons = [
            $trueIcon => $trueIcon,
            $falseIcon => $falseIcon,
        ];

        return $this;
    }

    public function toLaraviltProps(): array
    {
        $props = parent::toLaraviltProps();

        // Get the formatted state (applies formatStateUsing if set)
        $formattedState = $this->formatState($this->state);

        // Update state in props to use formatted value
        $props['state'] = $formattedState;

        // Scalar states (including 0 and false) can be looked up in the colors / icons maps
        $stateKey = $this->getStateKey($formattedState);

        // Apply color based on formatted state if in colors mapping
        if ($stateKey !== null && isset($this->colors[$stateKey])) {
            $props['color'] = $this->colors[$stateKey];
        } elseif (! $props['color']) {
            // Default fallback color
            $props['color'] = 'gray';
        }

        // Apply icon based on formatted state if in icons mapping
        if ($stateKey !== null && isset($this->icons[$stateKey])) {
            $props['icon'] = $this->icons[$stateKey];
        }

        return array_merge($props, [
            'colors' => $this->colors,
            'icons' => $this->icons,
        ]);
    }
}
