<?php

use Laravilt\Infolists\Entries\TextEntry;

beforeEach(function () {
    $this->entry = TextEntry::make('test_field');
});

it('can be instantiated with make method', function () {
    $entry = TextEntry::make('name');

    expect($entry)->toBeInstanceOf(TextEntry::class)
        ->and($entry->getName())->toBe('name');
});

it('can set limit', function () {
    $this->entry->limit(50);

    $props = $this->entry->toInertiaProps();

    expect($props['limit'])->toBe(50);
});

it('can enable wrap', function () {
    $this->entry->wrap();

    $props = $this->entry->toInertiaProps();

    expect($props['wrap'])->toBeTrue();
});

it('is not wrapped by default', function () {
    $props = $this->entry->toInertiaProps();

    expect($props['wrap'])->toBeFalse();
});

it('can enable markdown', function () {
    $this->entry->markdown();

    $props = $this->entry->toInertiaProps();

    expect($props['markdown'])->toBeTrue();
});

it('is not markdown by default', function () {
    $props = $this->entry->toInertiaProps();

    expect($props['markdown'])->toBeFalse();
});

it('can enable html', function () {
    $this->entry->html();

    $props = $this->entry->toInertiaProps();

    expect($props['html'])->toBeTrue();
});

it('is not html by default', function () {
    $props = $this->entry->toInertiaProps();

    expect($props['html'])->toBeFalse();
});

it('can set prefix', function () {
    $this->entry->prefix('$');

    $props = $this->entry->toInertiaProps();

    expect($props['prefix'])->toBe('$');
});

it('can set suffix', function () {
    $this->entry->suffix(' USD');

    $props = $this->entry->toInertiaProps();

    expect($props['suffix'])->toBe(' USD');
});

it('can enable badge', function () {
    $this->entry->badge();

    $props = $this->entry->toInertiaProps();

    expect($props['badge'])->toBeTrue();
});

it('is not badge by default', function () {
    $props = $this->entry->toInertiaProps();

    expect($props['badge'])->toBeFalse();
});

it('can format as money', function () {
    $entry = TextEntry::make('price')->money('USD');
    $entry->state = 100;

    $formatted = $entry->formatState(100);

    expect($formatted)->toBe('USD 100.00');
});

it('can format as money with division', function () {
    $entry = TextEntry::make('price')->money('USD', true);
    $entry->state = 10050;

    $formatted = $entry->formatState(10050);

    expect($formatted)->toBe('USD 100.50');
});

it('handles null money values', function () {
    $entry = TextEntry::make('price')->money('USD');

    $formatted = $entry->formatState(null);

    expect($formatted)->toBeNull();
});

it('handles empty string money values', function () {
    $entry = TextEntry::make('price')->money('USD');

    $formatted = $entry->formatState('');

    expect($formatted)->toBeNull();
});

it('can format as date', function () {
    $entry = TextEntry::make('created_at')->date();

    $formatted = $entry->formatState('2024-01-15');

    expect($formatted)->toBe('Jan 15, 2024');
});

it('can format as date with custom format', function () {
    $entry = TextEntry::make('created_at')->date('Y-m-d');

    $formatted = $entry->formatState('2024-01-15 10:30:00');

    expect($formatted)->toBe('2024-01-15');
});

it('can format as datetime', function () {
    $entry = TextEntry::make('created_at')->dateTime();

    $formatted = $entry->formatState('2024-01-15 14:30:00');

    expect($formatted)->toBe('Jan 15, 2024 14:30');
});

it('can format as since (relative time)', function () {
    $entry = TextEntry::make('created_at')->since();

    $now = now();
    $formatted = $entry->formatState($now->copy()->subDays(2));

    expect($formatted)->toContain('2 days ago');
});

it('handles null date values', function () {
    $entry = TextEntry::make('created_at')->date();

    $formatted = $entry->formatState(null);

    expect($formatted)->toBeNull();
});

it('converts to Inertia props with all text entry properties', function () {
    $entry = TextEntry::make('description')
        ->label('Description')
        ->limit(100)
        ->wrap()
        ->markdown()
        ->prefix('Note: ')
        ->suffix(' (end)')
        ->badge();

    $entry->state = 'Test description';

    $props = $entry->toInertiaProps();

    expect($props)->toHaveKey('limit')
        ->and($props)->toHaveKey('wrap')
        ->and($props)->toHaveKey('markdown')
        ->and($props)->toHaveKey('html')
        ->and($props)->toHaveKey('prefix')
        ->and($props)->toHaveKey('suffix')
        ->and($props)->toHaveKey('badge')
        ->and($props['limit'])->toBe(100)
        ->and($props['wrap'])->toBeTrue()
        ->and($props['markdown'])->toBeTrue()
        ->and($props['prefix'])->toBe('Note: ')
        ->and($props['suffix'])->toBe(' (end)')
        ->and($props['badge'])->toBeTrue();
});

it('can chain multiple methods', function () {
    $entry = TextEntry::make('title')
        ->limit(50)
        ->wrap()
        ->badge()
        ->prefix('Title: ')
        ->suffix(' *')
        ->color('blue');

    $props = $entry->toInertiaProps();

    expect($props['limit'])->toBe(50)
        ->and($props['wrap'])->toBeTrue()
        ->and($props['badge'])->toBeTrue()
        ->and($props['prefix'])->toBe('Title: ')
        ->and($props['suffix'])->toBe(' *')
        ->and($props['color'])->toBe('blue');
});

it('handles both markdown and html flags', function () {
    $entry = TextEntry::make('content')
        ->markdown()
        ->html();

    $props = $this->entry->toInertiaProps();

    expect($props['markdown'])->toBeFalse()
        ->and($props['html'])->toBeFalse();
});

it('formats money with different currencies', function () {
    $entry = TextEntry::make('price')->money('EUR');

    $formatted = $entry->formatState(50.5);

    expect($formatted)->toBe('EUR 50.50');
});

it('can disable wrap conditionally', function () {
    $this->entry->wrap(false);

    $props = $this->entry->toInertiaProps();

    expect($props['wrap'])->toBeFalse();
});

it('can disable markdown conditionally', function () {
    $this->entry->markdown(false);

    $props = $this->entry->toInertiaProps();

    expect($props['markdown'])->toBeFalse();
});

it('can disable html conditionally', function () {
    $this->entry->html(false);

    $props = $this->entry->toInertiaProps();

    expect($props['html'])->toBeFalse();
});

it('can disable badge conditionally', function () {
    $this->entry->badge(false);

    $props = $this->entry->toInertiaProps();

    expect($props['badge'])->toBeFalse();
});
