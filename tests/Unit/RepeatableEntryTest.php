<?php

use Laravilt\Infolists\Entries\RepeatableEntry;
use Laravilt\Infolists\Entries\TextEntry;

beforeEach(function () {
    $this->entry = RepeatableEntry::make('items');
});

it('can be instantiated with make method', function () {
    $entry = RepeatableEntry::make('comments');

    expect($entry)->toBeInstanceOf(RepeatableEntry::class)
        ->and($entry->getName())->toBe('comments');
});

it('can set schema with entries', function () {
    $this->entry->schema([
        TextEntry::make('title'),
        TextEntry::make('description'),
    ]);

    $props = $this->entry->toInertiaProps();

    expect($props)->toHaveKey('schema')
        ->and($props['schema'])->toHaveCount(2);
});

it('is not collapsible by default', function () {
    $props = $this->entry->toInertiaProps();

    expect($props['collapsible'])->toBeFalse();
});

it('is not collapsed by default', function () {
    $props = $this->entry->toInertiaProps();

    expect($props['collapsed'])->toBeFalse();
});

it('can enable collapsible', function () {
    $this->entry->collapsible();

    $props = $this->entry->toInertiaProps();

    expect($props['collapsible'])->toBeTrue();
});

it('can set collapsed', function () {
    $this->entry->collapsed();

    $props = $this->entry->toInertiaProps();

    expect($props['collapsed'])->toBeTrue()
        ->and($props['collapsible'])->toBeTrue();
});

it('collapsed also enables collapsible', function () {
    $this->entry->collapsed();

    $props = $this->entry->toInertiaProps();

    expect($props['collapsible'])->toBeTrue();
});

it('has default empty message', function () {
    $props = $this->entry->toInertiaProps();

    expect($props['emptyMessage'])->toBe('No items');
});

it('can set custom empty message', function () {
    $this->entry->emptyMessage('No data available');

    $props = $this->entry->toInertiaProps();

    expect($props['emptyMessage'])->toBe('No data available');
});

it('serializes schema entries in props', function () {
    $this->entry->schema([
        TextEntry::make('name')->label('Name'),
        TextEntry::make('email')->label('Email'),
    ]);

    $props = $this->entry->toInertiaProps();

    expect($props['schema'])->toBeArray()
        ->and($props['schema'][0])->toHaveKey('component')
        ->and($props['schema'][0]['component'])->toBe('text_entry')
        ->and($props['schema'][0])->toHaveKey('label')
        ->and($props['schema'][0]['label'])->toBe('Name');
});

it('converts to Inertia props with all repeatable entry properties', function () {
    $entry = RepeatableEntry::make('team_members')
        ->label('Team Members')
        ->schema([
            TextEntry::make('name'),
            TextEntry::make('role'),
        ])
        ->collapsible()
        ->collapsed()
        ->emptyMessage('No team members');

    $props = $entry->toInertiaProps();

    expect($props)->toHaveKey('schema')
        ->and($props)->toHaveKey('collapsible')
        ->and($props)->toHaveKey('collapsed')
        ->and($props)->toHaveKey('emptyMessage')
        ->and($props['collapsible'])->toBeTrue()
        ->and($props['collapsed'])->toBeTrue()
        ->and($props['emptyMessage'])->toBe('No team members')
        ->and($props['schema'])->toHaveCount(2);
});

it('can chain multiple methods', function () {
    $entry = RepeatableEntry::make('addresses')
        ->label('Addresses')
        ->schema([TextEntry::make('street')])
        ->collapsible()
        ->emptyMessage('No addresses')
        ->placeholder('Addresses will appear here');

    $props = $entry->toInertiaProps();

    expect($props['collapsible'])->toBeTrue()
        ->and($props['emptyMessage'])->toBe('No addresses')
        ->and($props['placeholder'])->toBe('Addresses will appear here');
});

it('can disable collapsible conditionally', function () {
    $this->entry->collapsible(false);

    $props = $this->entry->toInertiaProps();

    expect($props['collapsible'])->toBeFalse();
});

it('can disable collapsed conditionally', function () {
    $this->entry->collapsed(false);

    $props = $this->entry->toInertiaProps();

    expect($props['collapsed'])->toBeFalse();
});

it('handles empty schema', function () {
    $this->entry->schema([]);

    $props = $this->entry->toInertiaProps();

    expect($props['schema'])->toBeEmpty();
});

it('passes entry state to schema items', function () {
    $this->entry->schema([
        TextEntry::make('title'),
        TextEntry::make('content'),
    ]);

    $this->entry->state = [
        ['title' => 'First', 'content' => 'Content 1'],
        ['title' => 'Second', 'content' => 'Content 2'],
    ];

    $props = $this->entry->toInertiaProps();

    expect($props['state'])->toBeArray()
        ->and($props['state'])->toHaveCount(2);
});

it('handles null state', function () {
    $this->entry->state = null;

    $props = $this->entry->toInertiaProps();

    expect($props['state'])->toBeNull();
});

it('can nest different entry types', function () {
    $this->entry->schema([
        TextEntry::make('name'),
        TextEntry::make('email'),
        TextEntry::make('phone'),
    ]);

    $props = $this->entry->toInertiaProps();

    expect($props['schema'])->toHaveCount(3);
});

it('combines with base Entry features', function () {
    $entry = RepeatableEntry::make('comments')
        ->label('Comments')
        ->placeholder('No comments')
        ->schema([TextEntry::make('text')])
        ->collapsible()
        ->color('gray');

    $props = $entry->toInertiaProps();

    expect($props)->toHaveKey('label')
        ->and($props)->toHaveKey('placeholder')
        ->and($props)->toHaveKey('color')
        ->and($props['placeholder'])->toBe('No comments')
        ->and($props['color'])->toBe('gray');
});

it('handles complex nested data', function () {
    $data = [
        ['name' => 'Item 1', 'value' => 100],
        ['name' => 'Item 2', 'value' => 200],
        ['name' => 'Item 3', 'value' => 300],
    ];

    $this->entry->state = $data;

    $props = $this->entry->toInertiaProps();

    expect($props['state'])->toBe($data);
});
