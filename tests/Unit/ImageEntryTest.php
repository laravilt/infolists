<?php

use Laravilt\Infolists\Entries\ImageEntry;

beforeEach(function () {
    $this->entry = ImageEntry::make('avatar');
});

it('can be instantiated with make method', function () {
    $entry = ImageEntry::make('photo');

    expect($entry)->toBeInstanceOf(ImageEntry::class)
        ->and($entry->getName())->toBe('photo');
});

it('can set width', function () {
    $this->entry->width(200);

    $props = $this->entry->toInertiaProps();

    expect($props['width'])->toBe(200);
});

it('can set height', function () {
    $this->entry->height(300);

    $props = $this->entry->toInertiaProps();

    expect($props['height'])->toBe(300);
});

it('can set size for both width and height', function () {
    $this->entry->size(150);

    $props = $this->entry->toInertiaProps();

    expect($props['width'])->toBe(150)
        ->and($props['height'])->toBe(150);
});

it('can enable rounded', function () {
    $this->entry->rounded();

    $props = $this->entry->toInertiaProps();

    expect($props['rounded'])->toBeTrue();
});

it('is not rounded by default', function () {
    $props = $this->entry->toInertiaProps();

    expect($props['rounded'])->toBeFalse();
});

it('can enable circular', function () {
    $this->entry->circular();

    $props = $this->entry->toInertiaProps();

    expect($props['circular'])->toBeTrue()
        ->and($props['rounded'])->toBeTrue();
});

it('is not circular by default', function () {
    $props = $this->entry->toInertiaProps();

    expect($props['circular'])->toBeFalse();
});

it('circular also enables rounded', function () {
    $this->entry->circular();

    $props = $this->entry->toInertiaProps();

    expect($props['circular'])->toBeTrue()
        ->and($props['rounded'])->toBeTrue();
});

it('can set alt text', function () {
    $this->entry->alt('User avatar');

    $props = $this->entry->toInertiaProps();

    expect($props['alt'])->toBe('User avatar');
});

it('alt is null by default', function () {
    $props = $this->entry->toInertiaProps();

    expect($props['alt'])->toBeNull();
});

it('can set default image', function () {
    $this->entry->defaultImage('/images/default-avatar.png');

    $props = $this->entry->toInertiaProps();

    expect($props['defaultImage'])->toContain('default-avatar.png');
});

it('converts relative URLs to absolute', function () {
    $this->entry->state = 'images/photo.jpg';

    $props = $this->entry->toInertiaProps();

    expect($props['state'])->toContain('images/photo.jpg');
});

it('preserves absolute URLs', function () {
    $this->entry->state = 'https://example.com/image.jpg';

    $props = $this->entry->toInertiaProps();

    expect($props['state'])->toBe('https://example.com/image.jpg');
});

it('converts to Inertia props with all image entry properties', function () {
    $entry = ImageEntry::make('profile_picture')
        ->label('Profile Picture')
        ->width(200)
        ->height(200)
        ->circular()
        ->alt('User profile')
        ->defaultImage('/images/default.png');

    $entry->state = 'https://example.com/user.jpg';

    $props = $entry->toInertiaProps();

    expect($props)->toHaveKey('width')
        ->and($props)->toHaveKey('height')
        ->and($props)->toHaveKey('rounded')
        ->and($props)->toHaveKey('circular')
        ->and($props)->toHaveKey('alt')
        ->and($props)->toHaveKey('defaultImage')
        ->and($props['width'])->toBe(200)
        ->and($props['height'])->toBe(200)
        ->and($props['circular'])->toBeTrue()
        ->and($props['alt'])->toBe('User profile');
});

it('can chain multiple methods', function () {
    $entry = ImageEntry::make('thumbnail')
        ->size(100)
        ->rounded()
        ->alt('Product thumbnail')
        ->defaultImage('/fallback.png')
        ->copyable();

    $props = $entry->toInertiaProps();

    expect($props['width'])->toBe(100)
        ->and($props['height'])->toBe(100)
        ->and($props['rounded'])->toBeTrue()
        ->and($props['alt'])->toBe('Product thumbnail')
        ->and($props['copyable'])->toBeTrue();
});

it('can disable rounded conditionally', function () {
    $this->entry->rounded(false);

    $props = $this->entry->toInertiaProps();

    expect($props['rounded'])->toBeFalse();
});

it('can disable circular conditionally', function () {
    $this->entry->circular(false);

    $props = $this->entry->toInertiaProps();

    expect($props['circular'])->toBeFalse();
});

it('handles null state gracefully', function () {
    $this->entry->state = null;

    $props = $this->entry->toInertiaProps();

    expect($props['state'])->toBeNull();
});

it('size method overrides previous width and height', function () {
    $this->entry->width(200)->height(300)->size(150);

    $props = $this->entry->toInertiaProps();

    expect($props['width'])->toBe(150)
        ->and($props['height'])->toBe(150);
});
