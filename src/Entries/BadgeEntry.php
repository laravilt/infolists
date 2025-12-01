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

        // Apply color based on formatted state if in colors mapping
        if ($formattedState && isset($this->colors[$formattedState])) {
            $props['color'] = $this->colors[$formattedState];
        } elseif (! $props['color']) {
            // Default fallback color
            $props['color'] = 'gray';
        }

        // Apply icon based on formatted state if in icons mapping
        if ($formattedState && isset($this->icons[$formattedState])) {
            $props['icon'] = $this->icons[$formattedState];
        }

        return array_merge($props, [
            'colors' => $this->colors,
            'icons' => $this->icons,
        ]);
    }
}
