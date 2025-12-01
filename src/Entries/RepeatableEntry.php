<?php

declare(strict_types=1);

namespace Laravilt\Infolists\Entries;

class RepeatableEntry extends Entry
{
    protected array $schema = [];

    protected bool $collapsible = false;

    protected bool $collapsed = false;

    protected ?string $emptyMessage = null;

    /**
     * @param  array<Entry>  $schema
     */
    public function schema(array $schema): static
    {
        $this->schema = $schema;

        return $this;
    }

    public function collapsible(bool $condition = true): static
    {
        $this->collapsible = $condition;

        return $this;
    }

    public function collapsed(bool $condition = true): static
    {
        $this->collapsed = $condition;
        $this->collapsible = true;

        return $this;
    }

    public function emptyMessage(string $message): static
    {
        $this->emptyMessage = $message;

        return $this;
    }

    public function toLaraviltProps(): array
    {
        // Serialize the schema entries
        $serializedSchema = array_map(
            fn ($entry) => $entry->toLaraviltProps(),
            $this->schema
        );

        return array_merge(parent::toLaraviltProps(), [
            'schema' => $serializedSchema,
            'collapsible' => $this->collapsible,
            'collapsed' => $this->collapsed,
            'emptyMessage' => $this->emptyMessage ?? 'No items',
        ]);
    }
}
