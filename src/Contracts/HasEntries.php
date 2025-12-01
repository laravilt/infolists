<?php

declare(strict_types=1);

namespace Laravilt\Infolists\Contracts;

interface HasEntries
{
    /**
     * @param  array<int, \Laravilt\Infolists\Entries\Entry>  $schema
     */
    public function schema(array $schema): static;

    /**
     * @return array<int, \Laravilt\Infolists\Entries\Entry>
     */
    public function getSchema(): array;
}
