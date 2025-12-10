<?php

namespace Laravilt\Infolists;

use Laravilt\Schemas\Schema;

class Infolist extends Schema
{
    protected string $view = 'laravilt-infolists::infolist';

    /**
     * Create a new infolist instance.
     * Override parent to make name optional.
     */
    public static function make(?string $name = null): static
    {
        $static = app(static::class);
        $static->name = $name ?? 'infolist';
        $static->setUp();

        return $static;
    }

    /**
     * Get all visible entries in the schema.
     */
    public function getVisibleEntries(): array
    {
        return $this->getVisibleComponents();
    }
}
