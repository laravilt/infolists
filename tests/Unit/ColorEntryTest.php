<?php

use Laravilt\Infolists\Entries\ColorEntry;

beforeEach(function () {
    $this->entry = ColorEntry::make('color');
});

it('can be instantiated with make method', function () {
    $entry = ColorEntry::make('theme_color');

    expect($entry)->toBeInstanceOf(ColorEntry::class)
        ->and($entry->getName())->toBe('theme_color');
});

it('is copyable by default', function () {
    expect($this->entry->isCopyable())->toBeTrue();
});

it('shows label by default', function () {
    $props = $this->entry->toInertiaProps();

    expect($props['showLabel'])->toBeTrue();
});

it('can hide label', function () {
    $this->entry->showLabel(false);

    $props = $this->entry->toInertiaProps();

    expect($props['showLabel'])->toBeFalse();
});

it('can enable label conditionally', function () {
    $this->entry->showLabel(true);

    $props = $this->entry->toInertiaProps();

    expect($props['showLabel'])->toBeTrue();
});

it('can set size', function () {
    $this->entry->size('lg');

    $props = $this->entry->toInertiaProps();

    expect($props['size'])->toBe('lg');
});

it('size is null by default', function () {
    $props = $this->entry->toInertiaProps();

    expect($props['size'])->toBeNull();
});

it('converts to Inertia props with all color entry properties', function () {
    $entry = ColorEntry::make('brand_color')
        ->label('Brand Color')
        ->showLabel()
        ->size('md');

    $entry->state = '#3B82F6';

    $props = $entry->toInertiaProps();

    expect($props)->toHaveKey('showLabel')
        ->and($props)->toHaveKey('size')
        ->and($props)->toHaveKey('copyable')
        ->and($props['showLabel'])->toBeTrue()
        ->and($props['size'])->toBe('md')
        ->and($props['copyable'])->toBeTrue()
        ->and($props['state'])->toBe('#3B82F6');
});

it('can chain multiple methods', function () {
    $entry = ColorEntry::make('accent_color')
        ->label('Accent')
        ->showLabel(false)
        ->size('xl')
        ->copyable(false);

    $props = $entry->toInertiaProps();

    expect($props['showLabel'])->toBeFalse()
        ->and($props['size'])->toBe('xl')
        ->and($props['copyable'])->toBeFalse();
});

it('works with different size values', function () {
    $sizes = ['sm', 'md', 'lg', 'xl'];

    foreach ($sizes as $size) {
        $entry = ColorEntry::make('color')->size($size);
        $props = $entry->toInertiaProps();

        expect($props['size'])->toBe($size);
    }
});

it('handles hex color values', function () {
    $this->entry->state = '#FF5733';

    $props = $this->entry->toInertiaProps();

    expect($props['state'])->toBe('#FF5733');
});

it('handles rgb color values', function () {
    $this->entry->state = 'rgb(255, 87, 51)';

    $props = $this->entry->toInertiaProps();

    expect($props['state'])->toBe('rgb(255, 87, 51)');
});

it('handles named color values', function () {
    $this->entry->state = 'blue';

    $props = $this->entry->toInertiaProps();

    expect($props['state'])->toBe('blue');
});

it('handles null color values', function () {
    $this->entry->state = null;

    $props = $this->entry->toInertiaProps();

    expect($props['state'])->toBeNull();
});

it('can be made non-copyable', function () {
    $this->entry->copyable(false);

    expect($this->entry->isCopyable())->toBeFalse();
});

it('combines with base Entry features', function () {
    $entry = ColorEntry::make('primary_color')
        ->label('Primary Color')
        ->placeholder('No color set')
        ->size('lg')
        ->showLabel();

    $props = $entry->toInertiaProps();

    expect($props)->toHaveKey('label')
        ->and($props)->toHaveKey('placeholder')
        ->and($props)->toHaveKey('size')
        ->and($props)->toHaveKey('showLabel')
        ->and($props['placeholder'])->toBe('No color set')
        ->and($props['size'])->toBe('lg');
});
