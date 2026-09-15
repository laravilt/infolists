<?php

use Laravilt\Infolists\Entries\BadgeEntry;

it('applies the mapped color and icon for a 0 state', function () {
    $props = BadgeEntry::make('status')
        ->state(0)
        ->colors([0 => 'danger', 1 => 'success'])
        ->icons([0 => 'x-circle', 1 => 'check-circle'])
        ->toLaraviltProps();

    expect($props['state'])->toBe(0)
        ->and($props['color'])->toBe('danger')
        ->and($props['icon'])->toBe('x-circle');
});

it('applies the mapped color and icon for a "0" string state', function () {
    $props = BadgeEntry::make('status')
        ->state('0')
        ->colors(['0' => 'warning'])
        ->icons(['0' => 'clock'])
        ->toLaraviltProps();

    expect($props['color'])->toBe('warning')
        ->and($props['icon'])->toBe('clock');
});

it('applies the mapped color for a false state', function () {
    $props = BadgeEntry::make('active')
        ->state(false)
        ->colors([0 => 'danger', 1 => 'success'])
        ->toLaraviltProps();

    expect($props['state'])->toBeFalse()
        ->and($props['color'])->toBe('danger');
});

it('falls back to gray for states that cannot be map keys', function () {
    $props = BadgeEntry::make('tags')
        ->state(['a', 'b'])
        ->colors(['a' => 'success'])
        ->toLaraviltProps();

    expect($props['color'])->toBe('gray');
});

it('falls back to gray for a null state', function () {
    $props = BadgeEntry::make('status')
        ->state(null)
        ->colors(['' => 'success'])
        ->toLaraviltProps();

    expect($props['color'])->toBe('gray');
});
