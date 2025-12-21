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

    /**
     * The record instance being displayed.
     */
    protected mixed $record = null;

    /**
     * Set the record instance (used by Schema::fillComponents).
     */
    public function setRecord(mixed $record): static
    {
        $this->record = $record;

        return $this;
    }

    protected bool $copyable = false;

    protected ?Closure $formatStateUsing = null;

    protected string|Closure|null $color = null;

    protected string|Closure|null $icon = null;

    protected string|Closure|null $iconColor = null;

    protected ?string $tooltip = null;

    /**
     * Set up the entry with default placeholder.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Set default placeholder for entries
        $this->placeholder = $this->placeholder ?? '-';
    }

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

    public function color(string|Closure|null $color): static
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get the evaluated color value.
     */
    public function getColor(): ?string
    {
        if ($this->color instanceof Closure) {
            return ($this->color)($this->state);
        }

        return $this->color;
    }

    public function icon(string|Closure|null $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get the evaluated icon value.
     */
    public function getIcon(): ?string
    {
        if ($this->icon instanceof Closure) {
            return ($this->icon)($this->state);
        }

        return $this->icon;
    }

    public function iconColor(string|Closure|null $color): static
    {
        $this->iconColor = $color;

        return $this;
    }

    /**
     * Get the evaluated icon color value.
     */
    public function getIconColor(): ?string
    {
        if ($this->iconColor instanceof Closure) {
            return ($this->iconColor)($this->state);
        }

        return $this->iconColor;
    }

    public function state(mixed $state): static
    {
        // Store the actual state value (don't fall back to placeholder)
        // Placeholder is only used for DISPLAY in the frontend, not as actual data
        $this->state = $state;

        return $this;
    }

    public function fill(Model $record): static
    {
        // Store the record for access by closures
        $this->record = $record;

        // Handle dot notation for relationships (e.g., "groups.name")
        if (str_contains($this->name, '.')) {
            $parts = explode('.', $this->name);
            $relationship = $parts[0];
            $attribute = $parts[1];

            // Load the relationship if not already loaded and the method exists
            if (! $record->relationLoaded($relationship) && method_exists($record, $relationship)) {
                try {
                    $record->load($relationship);
                } catch (\Throwable $e) {
                    // Relationship couldn't be loaded, continue with null
                }
            }

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

        // Format the state - don't fall back to placeholder, it's only for display
        $this->state = $this->formatState($value);

        return $this;
    }

    /**
     * Get the record instance being displayed.
     */
    public function getRecord(): mixed
    {
        return $this->record;
    }

    /**
     * Override parent's toLaraviltProps to include Entry-specific props
     */
    public function toLaraviltProps(): array
    {
        return array_merge(parent::toLaraviltProps(), [
            'copyable' => $this->copyable,
            'color' => $this->getColor(),
            'icon' => $this->getIcon(),
            'iconColor' => $this->getIconColor(),
            'tooltip' => $this->tooltip,
            'placeholder' => $this->placeholder,
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
