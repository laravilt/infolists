<?php

declare(strict_types=1);

namespace Laravilt\Infolists\Contracts;

use Laravilt\Infolists\Entries\Entry;

interface HasEntries
{
    /**
     * @param  array<int, Entry>  $schema
     */
    public function schema(array $schema): static;

    /**
     * @return array<int, Entry>
     */
    public function getSchema(): array;
}
