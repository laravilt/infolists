<?php

declare(strict_types=1);

namespace Laravilt\Infolists\Entries;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Laravilt\Support\Component;
use Laravilt\Support\Concerns\InteractsWithState;

/**
 * @property mixed $state
 */
abstract class Entry extends Component
{
    use InteractsWithState;

    protected bool $copyable = false;

    protected ?Closure $formatStateUsing = null;

    protected ?string $color = null;

    protected ?string $icon = null;

    protected ?string $tooltip = null;

    public function copyable(bool $condition = true): static
    {
        $this->copyable = $condition;

        return $this;
    }

    public function isCopyable(): bool
    {
        return $this->copyable;
    }

    public function formatStateUsing(Closure $callback): static
    {
        $this->formatStateUsing = $callback;

        return $this;
    }

    public function formatState(mixed $state): mixed
    {
        if ($this->formatStateUsing) {
            return ($this->formatStateUsing)($state);
        }

        return $state;
    }

    public function color(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function icon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function state(mixed $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function fill(Model $record): static
    {
        // Handle dot notation for relationships (e.g., "groups.name")
        if (str_contains($this->name, '.')) {
            $parts = explode('.', $this->name);
            $relationship = $parts[0];
            $attribute = $parts[1];

            // Check if the relationship exists and is loaded
            if ($record->relationLoaded($relationship)) {
                $related = $record->{$relationship};

                // If it's a collection, pluck the attribute
                if ($related instanceof \Illuminate\Support\Collection) {
                    $value = $related->pluck($attribute)->toArray();
                } elseif ($related) {
                    // If it's a single model, get the attribute
                    $value = $related->{$attribute} ?? null;
                } else {
                    $value = null;
                }
            } else {
                $value = null;
            }
        } else {
            $value = $record->{$this->name} ?? null;
        }

        $this->state = $this->formatState($value);

        return $this;
    }

    /**
     * Override parent's toLaraviltProps to include Entry-specific props
     */
    public function toLaraviltProps(): array
    {
        return array_merge(parent::toLaraviltProps(), [
            'copyable' => $this->copyable,
            'color' => $this->color,
            'icon' => $this->icon,
            'tooltip' => $this->tooltip,
        ]);
    }

    /**
     * Alias for toLaraviltProps for backwards compatibility with tests
     */
    public function toInertiaProps(): array
    {
        return $this->toLaraviltProps();
    }
}
