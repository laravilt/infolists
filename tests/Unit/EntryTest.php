<?php

use Illuminate\Database\Eloquent\Model;
use Laravilt\Infolists\Entries\TextEntry;

beforeEach(function () {
    $this->entry = TextEntry::make('test_field');
});

it('can be instantiated with make method', function () {
    $entry = TextEntry::make('name');

    expect($entry)->toBeInstanceOf(TextEntry::class)
        ->and($entry->getName())->toBe('name');
});

it('can set and check copyable', function () {
    $this->entry->copyable();

    expect($this->entry->isCopyable())->toBeTrue();
});

it('is not copyable by default', function () {
    expect($this->entry->isCopyable())->toBeFalse();
});

it('can set copyable conditionally', function () {
    $this->entry->copyable(false);

    expect($this->entry->isCopyable())->toBeFalse();
});

it('can set placeholder', function () {
    $this->entry->placeholder('N/A');

    $props = $this->entry->toInertiaProps();

    expect($props['placeholder'])->toBe('N/A');
});

it('has default placeholder', function () {
    $props = $this->entry->toInertiaProps();

    expect($props['placeholder'])->toBe('-');
});

it('can format state using closure', function () {
    $this->entry->formatStateUsing(fn ($state) => strtoupper($state));
    $this->entry->state = 'hello';

    $formatted = $this->entry->formatState('hello');

    expect($formatted)->toBe('HELLO');
});

it('returns state unchanged when no formatter is set', function () {
    $formatted = $this->entry->formatState('test');

    expect($formatted)->toBe('test');
});

it('can set color', function () {
    $this->entry->color('blue');

    $props = $this->entry->toInertiaProps();

    expect($props['color'])->toBe('blue');
});

it('can set icon', function () {
    $this->entry->icon('heroicon-o-user');

    $props = $this->entry->toInertiaProps();

    expect($props['icon'])->toBe('heroicon-o-user');
});

it('fills state from model attribute', function () {
    $record = new class extends Model
    {
        protected $fillable = ['name', 'email'];
    };
    $record->name = 'John Doe';
    $record->email = 'john@example.com';

    $entry = TextEntry::make('name');
    $entry->fill($record);

    expect($entry->getState())->toBe('John Doe');
});

it('handles dot notation for relationships', function () {
    $related = new class extends Model
    {
        protected $fillable = ['title'];

        public $title = 'Test Title';
    };

    $record = new class extends Model
    {
        protected $fillable = ['group'];

        public function group()
        {
            return $this->belongsTo(get_class($GLOBALS['related']));
        }
    };

    // Manually set the loaded relationship
    $record->setRelation('group', $related);

    $entry = TextEntry::make('group.title');
    $entry->fill($record);

    expect($entry->getState())->toBe('Test Title');
});

it('handles missing attributes gracefully', function () {
    $record = new class extends Model
    {
        protected $fillable = ['name'];
    };
    $record->name = 'Test';

    $entry = TextEntry::make('nonexistent');
    $entry->fill($record);

    // Missing attributes return the placeholder value
    expect($entry->getState())->toBe('-');
});

it('applies format state using when filling', function () {
    $record = new class extends Model
    {
        protected $fillable = ['name'];
    };
    $record->name = 'john';

    $entry = TextEntry::make('name')
        ->formatStateUsing(fn ($state) => ucfirst($state));

    $entry->fill($record);

    expect($entry->getState())->toBe('John');
});

it('converts to Inertia props with correct structure', function () {
    $entry = TextEntry::make('name')
        ->label('Full Name')
        ->color('blue')
        ->icon('heroicon-o-user')
        ->copyable();

    $entry->state = 'John Doe';

    $props = $entry->toInertiaProps();

    expect($props)->toBeArray()
        ->and($props)->toHaveKey('component')
        ->and($props)->toHaveKey('state')
        ->and($props)->toHaveKey('copyable')
        ->and($props)->toHaveKey('placeholder')
        ->and($props)->toHaveKey('color')
        ->and($props)->toHaveKey('icon')
        ->and($props['state'])->toBe('John Doe')
        ->and($props['copyable'])->toBeTrue()
        ->and($props['color'])->toBe('blue')
        ->and($props['icon'])->toBe('heroicon-o-user');
});

it('can chain multiple methods', function () {
    $entry = TextEntry::make('test')
        ->label('Test Label')
        ->placeholder('Test Placeholder')
        ->color('red')
        ->icon('heroicon-o-check')
        ->copyable();

    expect($entry->getLabel())->toBe('Test Label')
        ->and($entry->isCopyable())->toBeTrue();
});

it('handles collection relationships with dot notation', function () {
    $item1 = new class extends Model
    {
        public $name = 'Item 1';
    };
    $item2 = new class extends Model
    {
        public $name = 'Item 2';
    };

    $record = new class extends Model
    {
        public function items()
        {
            return $this->hasMany(stdClass::class);
        }
    };

    $collection = collect([$item1, $item2]);
    $record->setRelation('items', $collection);

    $entry = TextEntry::make('items.name');
    $entry->fill($record);

    expect($entry->getState())->toBe(['Item 1', 'Item 2']);
});
