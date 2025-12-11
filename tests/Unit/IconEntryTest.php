<?php

use Laravilt\Infolists\Entries\IconEntry;

beforeEach(function () {
    $this->entry = IconEntry::make('icon');
});

it('can be instantiated with make method', function () {
    $entry = IconEntry::make('icon');

    expect($entry)->toBeInstanceOf(IconEntry::class)
        ->and($entry->getName())->toBe('icon');
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

it('can enable circular', function () {
    $this->entry->circular();

    $props = $this->entry->toInertiaProps();

    expect($props['circular'])->toBeTrue();
});

it('is not circular by default', function () {
    $props = $this->entry->toInertiaProps();

    expect($props['circular'])->toBeFalse();
});

it('can set icon color', function () {
    $this->entry->iconColor('blue');

    $props = $this->entry->toInertiaProps();

    expect($props['iconColor'])->toBe('blue');
});

it('icon color is null by default', function () {
    $props = $this->entry->toInertiaProps();

    expect($props['iconColor'])->toBeNull();
});

it('can format as boolean with default icons', function () {
    $entry = IconEntry::make('is_verified')->boolean();

    // Test true state
    $entry->state = true;
    $trueProps = $entry->toInertiaProps();

    // Test false state
    $entry->state = false;
    $falseProps = $entry->toInertiaProps();

    expect($trueProps['state'])->toBe('check-circle')
        ->and($falseProps['state'])->toBe('x-circle');
});

it('can format as boolean with custom icons', function () {
    $entry = IconEntry::make('is_active')
        ->boolean('thumbs-up', 'thumbs-down');

    // Test true state
    $entry->state = true;
    $trueProps = $entry->toInertiaProps();

    // Test false state
    $entry->state = false;
    $falseProps = $entry->toInertiaProps();

    expect($trueProps['state'])->toBe('thumbs-up')
        ->and($falseProps['state'])->toBe('thumbs-down');
});

it('converts to Inertia props with all icon entry properties', function () {
    $entry = IconEntry::make('status_icon')
        ->label('Status')
        ->size('xl')
        ->circular()
        ->iconColor('success');

    $entry->state = 'heroicon-o-check';

    $props = $entry->toInertiaProps();

    expect($props)->toHaveKey('size')
        ->and($props)->toHaveKey('circular')
        ->and($props)->toHaveKey('iconColor')
        ->and($props['size'])->toBe('xl')
        ->and($props['circular'])->toBeTrue()
        ->and($props['iconColor'])->toBe('success');
});

it('can chain multiple methods', function () {
    $entry = IconEntry::make('badge_icon')
        ->label('Badge')
        ->size('md')
        ->circular()
        ->iconColor('primary')
        ->copyable();

    $entry->state = 'heroicon-o-star';

    $props = $entry->toInertiaProps();

    expect($props['size'])->toBe('md')
        ->and($props['circular'])->toBeTrue()
        ->and($props['iconColor'])->toBe('primary')
        ->and($props['copyable'])->toBeTrue();
});

it('can disable circular conditionally', function () {
    $this->entry->circular(false);

    $props = $this->entry->toInertiaProps();

    expect($props['circular'])->toBeFalse();
});

it('works with different size values', function () {
    $sizes = ['sm', 'md', 'lg', 'xl', '2xl'];

    foreach ($sizes as $size) {
        $entry = IconEntry::make('icon')->size($size);
        $props = $entry->toInertiaProps();

        expect($props['size'])->toBe($size);
    }
});

it('can combine boolean formatting with styling', function () {
    $entry = IconEntry::make('verified')
        ->boolean()
        ->size('lg')
        ->circular()
        ->iconColor('success');

    $entry->state = true;
    $props = $entry->toInertiaProps();

    // Boolean state is converted to icon in props
    expect($props['state'])->toBe('check-circle')
        ->and($props['size'])->toBe('lg')
        ->and($props['circular'])->toBeTrue()
        ->and($props['iconColor'])->toBe('success');
});

it('state contains the icon name', function () {
    $this->entry->state = 'heroicon-o-user';

    $props = $this->entry->toInertiaProps();

    expect($props['state'])->toBe('heroicon-o-user');
});

it('can use state from parent icon property', function () {
    $this->entry->icon('heroicon-o-bell');
    $this->entry->state = null;

    $props = $this->entry->toInertiaProps();

    expect($props['icon'])->toBe('heroicon-o-bell');
});

it('handles null state gracefully', function () {
    $this->entry->state = null;

    $props = $this->entry->toInertiaProps();

    expect($props['state'])->toBeNull();
});

it('can format state and apply styling', function () {
    $entry = IconEntry::make('type')
        ->formatStateUsing(function ($state) {
            return match ($state) {
                'success' => 'heroicon-o-check-circle',
                'error' => 'heroicon-o-x-circle',
                'warning' => 'heroicon-o-exclamation-triangle',
                default => 'heroicon-o-information-circle',
            };
        })
        ->size('lg')
        ->circular();

    $entry->state = 'success';
    $formatted = $entry->formatState('success');

    expect($formatted)->toBe('heroicon-o-check-circle');
});

it('combines with base Entry features', function () {
    $entry = IconEntry::make('notification_icon')
        ->label('Notification Type')
        ->color('blue')
        ->size('md')
        ->circular()
        ->placeholder('No icon');

    $props = $entry->toInertiaProps();

    expect($props)->toHaveKey('label')
        ->and($props)->toHaveKey('color')
        ->and($props)->toHaveKey('size')
        ->and($props)->toHaveKey('placeholder')
        ->and($props['color'])->toBe('blue')
        ->and($props['size'])->toBe('md')
        ->and($props['placeholder'])->toBe('No icon');
});
