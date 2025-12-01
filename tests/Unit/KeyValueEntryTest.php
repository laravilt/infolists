<?php

use Laravilt\Infolists\Entries\KeyValueEntry;

beforeEach(function () {
    $this->entry = KeyValueEntry::make('metadata');
});

it('can be instantiated with make method', function () {
    $entry = KeyValueEntry::make('settings');

    expect($entry)->toBeInstanceOf(KeyValueEntry::class)
        ->and($entry->getName())->toBe('settings');
});

it('has default key label', function () {
    $props = $this->entry->toInertiaProps();

    expect($props['keyLabel'])->toBe('Key');
});

it('has default value label', function () {
    $props = $this->entry->toInertiaProps();

    expect($props['valueLabel'])->toBe('Value');
});

it('keys are not copyable by default', function () {
    $props = $this->entry->toInertiaProps();

    expect($props['copyableKeys'])->toBeFalse();
});

it('values are not copyable by default', function () {
    $props = $this->entry->toInertiaProps();

    expect($props['copyableValues'])->toBeFalse();
});

it('can set key label', function () {
    $this->entry->keyLabel('Property');

    $props = $this->entry->toInertiaProps();

    expect($props['keyLabel'])->toBe('Property');
});

it('can set value label', function () {
    $this->entry->valueLabel('Data');

    $props = $this->entry->toInertiaProps();

    expect($props['valueLabel'])->toBe('Data');
});

it('can enable copyable keys', function () {
    $this->entry->copyableKeys();

    $props = $this->entry->toInertiaProps();

    expect($props['copyableKeys'])->toBeTrue();
});

it('can enable copyable values', function () {
    $this->entry->copyableValues();

    $props = $this->entry->toInertiaProps();

    expect($props['copyableValues'])->toBeTrue();
});

it('can disable copyable keys conditionally', function () {
    $this->entry->copyableKeys(false);

    $props = $this->entry->toInertiaProps();

    expect($props['copyableKeys'])->toBeFalse();
});

it('can disable copyable values conditionally', function () {
    $this->entry->copyableValues(false);

    $props = $this->entry->toInertiaProps();

    expect($props['copyableValues'])->toBeFalse();
});

it('converts to Inertia props with all key-value entry properties', function () {
    $entry = KeyValueEntry::make('options')
        ->label('Options')
        ->keyLabel('Setting')
        ->valueLabel('Configuration')
        ->copyableKeys()
        ->copyableValues();

    $entry->state = ['debug' => true, 'timeout' => 30];

    $props = $entry->toInertiaProps();

    expect($props)->toHaveKey('keyLabel')
        ->and($props)->toHaveKey('valueLabel')
        ->and($props)->toHaveKey('copyableKeys')
        ->and($props)->toHaveKey('copyableValues')
        ->and($props['keyLabel'])->toBe('Setting')
        ->and($props['valueLabel'])->toBe('Configuration')
        ->and($props['copyableKeys'])->toBeTrue()
        ->and($props['copyableValues'])->toBeTrue()
        ->and($props['state'])->toBe(['debug' => true, 'timeout' => 30]);
});

it('can chain multiple methods', function () {
    $entry = KeyValueEntry::make('config')
        ->label('Configuration')
        ->keyLabel('Parameter')
        ->valueLabel('Setting')
        ->copyableKeys()
        ->copyableValues()
        ->placeholder('No configuration');

    $props = $entry->toInertiaProps();

    expect($props['keyLabel'])->toBe('Parameter')
        ->and($props['valueLabel'])->toBe('Setting')
        ->and($props['copyableKeys'])->toBeTrue()
        ->and($props['copyableValues'])->toBeTrue()
        ->and($props['placeholder'])->toBe('No configuration');
});

it('handles array data', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'role' => 'admin',
    ];
    $this->entry->state = $data;

    $props = $this->entry->toInertiaProps();

    expect($props['state'])->toBe($data);
});

it('handles empty array', function () {
    $this->entry->state = [];

    $props = $this->entry->toInertiaProps();

    expect($props['state'])->toBeEmpty();
});

it('handles null state', function () {
    $this->entry->state = null;

    $props = $this->entry->toInertiaProps();

    expect($props['state'])->toBeNull();
});

it('can enable both copyable keys and values', function () {
    $this->entry->copyableKeys()->copyableValues();

    $props = $this->entry->toInertiaProps();

    expect($props['copyableKeys'])->toBeTrue()
        ->and($props['copyableValues'])->toBeTrue();
});

it('can customize labels independently', function () {
    $this->entry->keyLabel('Field')->valueLabel('Content');

    $props = $this->entry->toInertiaProps();

    expect($props['keyLabel'])->toBe('Field')
        ->and($props['valueLabel'])->toBe('Content');
});

it('combines with base Entry features', function () {
    $entry = KeyValueEntry::make('attributes')
        ->label('Attributes')
        ->placeholder('No attributes')
        ->keyLabel('Attribute')
        ->valueLabel('Value')
        ->color('blue');

    $props = $entry->toInertiaProps();

    expect($props)->toHaveKey('label')
        ->and($props)->toHaveKey('placeholder')
        ->and($props)->toHaveKey('color')
        ->and($props['placeholder'])->toBe('No attributes')
        ->and($props['color'])->toBe('blue');
});

it('handles nested data structures', function () {
    $data = [
        'user' => ['name' => 'John', 'age' => 30],
        'settings' => ['theme' => 'dark', 'notifications' => true],
    ];
    $this->entry->state = $data;

    $props = $this->entry->toInertiaProps();

    expect($props['state'])->toBe($data);
});
