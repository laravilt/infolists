<?php

use Laravilt\Infolists\Entries\BadgeEntry;

beforeEach(function () {
    $this->entry = BadgeEntry::make('status');
});

it('can be instantiated with make method', function () {
    $entry = BadgeEntry::make('status');

    expect($entry)->toBeInstanceOf(BadgeEntry::class)
        ->and($entry->getName())->toBe('status');
});

it('can set colors mapping', function () {
    $this->entry->colors([
        'active' => 'success',
        'inactive' => 'danger',
        'pending' => 'warning',
    ]);

    $props = $this->entry->toInertiaProps();

    expect($props)->toHaveKey('colors')
        ->and($props['colors'])->toBe([
            'active' => 'success',
            'inactive' => 'danger',
            'pending' => 'warning',
        ]);
});

it('can set icons mapping', function () {
    $this->entry->icons([
        'active' => 'heroicon-o-check',
        'inactive' => 'heroicon-o-x-mark',
    ]);

    $props = $this->entry->toInertiaProps();

    expect($props)->toHaveKey('icons')
        ->and($props['icons'])->toBe([
            'active' => 'heroicon-o-check',
            'inactive' => 'heroicon-o-x-mark',
        ]);
});

it('applies color based on state', function () {
    $this->entry
        ->colors([
            'active' => 'success',
            'inactive' => 'danger',
        ])
        ->state('active');

    $props = $this->entry->toInertiaProps();

    expect($props['color'])->toBe('success');
});

it('uses default color when state not in mapping', function () {
    $this->entry
        ->colors(['active' => 'success'])
        ->color('gray')
        ->state('unknown');

    $props = $this->entry->toInertiaProps();

    expect($props['color'])->toBe('gray');
});

it('uses gray as fallback color when no color set', function () {
    $this->entry->state('unknown');

    $props = $this->entry->toInertiaProps();

    expect($props['color'])->toBe('gray');
});

it('applies icon based on state', function () {
    $this->entry
        ->icons([
            'active' => 'heroicon-o-check',
            'inactive' => 'heroicon-o-x-mark',
        ])
        ->state('active');

    $props = $this->entry->toInertiaProps();

    expect($props['icon'])->toBe('heroicon-o-check');
});

it('can format as boolean', function () {
    $entry = BadgeEntry::make('is_active')->bool();

    $trueFormatted = $entry->formatState(true);
    $falseFormatted = $entry->formatState(false);

    expect($trueFormatted)->toBe('heroicon-o-check-circle')
        ->and($falseFormatted)->toBe('heroicon-o-x-circle');
});

it('sets colors for boolean values', function () {
    $entry = BadgeEntry::make('is_active')->bool();
    $entry->state = true;

    $props = $entry->toInertiaProps();

    expect($props['colors'])->toHaveKey('heroicon-o-check-circle')
        ->and($props['colors']['heroicon-o-check-circle'])->toBe('success')
        ->and($props['colors'])->toHaveKey('heroicon-o-x-circle')
        ->and($props['colors']['heroicon-o-x-circle'])->toBe('danger');
});

it('sets icons for boolean values', function () {
    $entry = BadgeEntry::make('is_active')->bool();
    $entry->state = true;

    $props = $entry->toInertiaProps();

    expect($props['icons'])->toHaveKey('heroicon-o-check-circle')
        ->and($props['icons']['heroicon-o-check-circle'])->toBe('heroicon-o-check-circle')
        ->and($props['icons'])->toHaveKey('heroicon-o-x-circle')
        ->and($props['icons']['heroicon-o-x-circle'])->toBe('heroicon-o-x-circle');
});

it('can use custom icons for boolean', function () {
    $entry = BadgeEntry::make('verified')
        ->bool('heroicon-o-shield-check', 'heroicon-o-shield-exclamation');

    $trueFormatted = $entry->formatState(true);
    $falseFormatted = $entry->formatState(false);

    expect($trueFormatted)->toBe('heroicon-o-shield-check')
        ->and($falseFormatted)->toBe('heroicon-o-shield-exclamation');
});

it('converts to Inertia props with all badge entry properties', function () {
    $entry = BadgeEntry::make('status')
        ->label('Status')
        ->colors([
            'draft' => 'gray',
            'published' => 'success',
        ])
        ->icons([
            'draft' => 'heroicon-o-pencil',
            'published' => 'heroicon-o-check',
        ]);

    $entry->state = 'published';

    $props = $entry->toInertiaProps();

    expect($props)->toHaveKey('colors')
        ->and($props)->toHaveKey('icons')
        ->and($props['color'])->toBe('success')
        ->and($props['icon'])->toBe('heroicon-o-check');
});

it('can chain multiple methods', function () {
    $entry = BadgeEntry::make('role')
        ->label('User Role')
        ->colors([
            'admin' => 'danger',
            'user' => 'primary',
        ])
        ->icons([
            'admin' => 'heroicon-o-shield-check',
            'user' => 'heroicon-o-user',
        ])
        ->copyable();

    $entry->state = 'admin';

    $props = $entry->toInertiaProps();

    expect($props['color'])->toBe('danger')
        ->and($props['icon'])->toBe('heroicon-o-shield-check')
        ->and($props['copyable'])->toBeTrue();
});

it('handles empty colors array', function () {
    $this->entry->colors([]);
    $this->entry->state('active');

    $props = $this->entry->toInertiaProps();

    expect($props['colors'])->toBeEmpty()
        ->and($props['color'])->toBe('gray');
});

it('handles empty icons array', function () {
    $this->entry->icons([]);
    $this->entry->state('active');

    $props = $this->entry->toInertiaProps();

    expect($props['icons'])->toBeEmpty()
        ->and($props['icon'])->toBeNull();
});

it('can override default bool colors', function () {
    $entry = BadgeEntry::make('status')
        ->bool()
        ->color('primary');

    $entry->state = true;

    $props = $entry->toInertiaProps();

    // Bool should set colors mapping, so it should use the mapping
    expect($props['color'])->toBe('success');
});

it('formats state before applying color mapping', function () {
    $entry = BadgeEntry::make('status')
        ->formatStateUsing(fn ($state) => strtoupper($state))
        ->colors([
            'ACTIVE' => 'success',
            'INACTIVE' => 'danger',
        ]);

    $entry->state = 'active';

    $props = $entry->toInertiaProps();

    expect($props['state'])->toBe('ACTIVE')
        ->and($props['color'])->toBe('success');
});
