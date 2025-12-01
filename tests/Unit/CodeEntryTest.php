<?php

use Laravilt\Infolists\Entries\CodeEntry;

beforeEach(function () {
    $this->entry = CodeEntry::make('code');
});

it('can be instantiated with make method', function () {
    $entry = CodeEntry::make('snippet');

    expect($entry)->toBeInstanceOf(CodeEntry::class)
        ->and($entry->getName())->toBe('snippet');
});

it('has plaintext language by default', function () {
    $props = $this->entry->toInertiaProps();

    expect($props['language'])->toBe('plaintext');
});

it('is copyable by default', function () {
    expect($this->entry->isCopyable())->toBeTrue();
});

it('has line numbers enabled by default', function () {
    $props = $this->entry->toInertiaProps();

    expect($props['lineNumbers'])->toBeTrue();
});

it('can set language', function () {
    $this->entry->language('javascript');

    $props = $this->entry->toInertiaProps();

    expect($props['language'])->toBe('javascript');
});

it('can set max height', function () {
    $this->entry->maxHeight(400);

    $props = $this->entry->toInertiaProps();

    expect($props['maxHeight'])->toBe(400);
});

it('max height is null by default', function () {
    $props = $this->entry->toInertiaProps();

    expect($props['maxHeight'])->toBeNull();
});

it('can disable line numbers', function () {
    $this->entry->lineNumbers(false);

    $props = $this->entry->toInertiaProps();

    expect($props['lineNumbers'])->toBeFalse();
});

it('can enable line numbers conditionally', function () {
    $this->entry->lineNumbers(true);

    $props = $this->entry->toInertiaProps();

    expect($props['lineNumbers'])->toBeTrue();
});

it('can set language to json', function () {
    $this->entry->json();

    $props = $this->entry->toInertiaProps();

    expect($props['language'])->toBe('json');
});

it('can set language to php', function () {
    $this->entry->php();

    $props = $this->entry->toInertiaProps();

    expect($props['language'])->toBe('php');
});

it('can set language to javascript', function () {
    $this->entry->javascript();

    $props = $this->entry->toInertiaProps();

    expect($props['language'])->toBe('javascript');
});

it('can set language to typescript', function () {
    $this->entry->typescript();

    $props = $this->entry->toInertiaProps();

    expect($props['language'])->toBe('typescript');
});

it('can set language to python', function () {
    $this->entry->python();

    $props = $this->entry->toInertiaProps();

    expect($props['language'])->toBe('python');
});

it('can set language to sql', function () {
    $this->entry->sql();

    $props = $this->entry->toInertiaProps();

    expect($props['language'])->toBe('sql');
});

it('can set language to yaml', function () {
    $this->entry->yaml();

    $props = $this->entry->toInertiaProps();

    expect($props['language'])->toBe('yaml');
});

it('can set language to html', function () {
    $this->entry->html();

    $props = $this->entry->toInertiaProps();

    expect($props['language'])->toBe('html');
});

it('can set language to css', function () {
    $this->entry->css();

    $props = $this->entry->toInertiaProps();

    expect($props['language'])->toBe('css');
});

it('converts to Inertia props with all code entry properties', function () {
    $entry = CodeEntry::make('configuration')
        ->label('Config')
        ->language('json')
        ->maxHeight(300)
        ->lineNumbers();

    $entry->state = '{"key": "value"}';

    $props = $entry->toInertiaProps();

    expect($props)->toHaveKey('language')
        ->and($props)->toHaveKey('maxHeight')
        ->and($props)->toHaveKey('lineNumbers')
        ->and($props)->toHaveKey('copyable')
        ->and($props['language'])->toBe('json')
        ->and($props['maxHeight'])->toBe(300)
        ->and($props['lineNumbers'])->toBeTrue()
        ->and($props['copyable'])->toBeTrue()
        ->and($props['state'])->toBe('{"key": "value"}');
});

it('can chain multiple methods', function () {
    $entry = CodeEntry::make('script')
        ->label('Script')
        ->javascript()
        ->maxHeight(500)
        ->lineNumbers(false)
        ->copyable(false);

    $props = $entry->toInertiaProps();

    expect($props['language'])->toBe('javascript')
        ->and($props['maxHeight'])->toBe(500)
        ->and($props['lineNumbers'])->toBeFalse()
        ->and($props['copyable'])->toBeFalse();
});

it('handles multiline code', function () {
    $code = "function hello() {\n    console.log('Hello');\n}";
    $this->entry->state = $code;

    $props = $this->entry->toInertiaProps();

    expect($props['state'])->toBe($code);
});

it('handles null code values', function () {
    $this->entry->state = null;

    $props = $this->entry->toInertiaProps();

    expect($props['state'])->toBeNull();
});

it('can be made non-copyable', function () {
    $this->entry->copyable(false);

    expect($this->entry->isCopyable())->toBeFalse();
});

it('language method overrides shorthand methods', function () {
    $this->entry->json()->language('php');

    $props = $this->entry->toInertiaProps();

    expect($props['language'])->toBe('php');
});

it('shorthand methods override language method', function () {
    $this->entry->language('php')->json();

    $props = $this->entry->toInertiaProps();

    expect($props['language'])->toBe('json');
});

it('combines with base Entry features', function () {
    $entry = CodeEntry::make('example_code')
        ->label('Example')
        ->placeholder('No code provided')
        ->php()
        ->maxHeight(600)
        ->lineNumbers();

    $props = $entry->toInertiaProps();

    expect($props)->toHaveKey('label')
        ->and($props)->toHaveKey('placeholder')
        ->and($props)->toHaveKey('language')
        ->and($props['placeholder'])->toBe('No code provided')
        ->and($props['language'])->toBe('php');
});
