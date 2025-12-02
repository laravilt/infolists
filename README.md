![infolists](https://raw.githubusercontent.com/laravilt/infolists/master/arts/screenshot.jpg)

# Laravilt Infolists

[![Latest Stable Version](https://poser.pugx.org/laravilt/infolists/version.svg)](https://packagist.org/packages/laravilt/infolists)
[![License](https://poser.pugx.org/laravilt/infolists/license.svg)](https://packagist.org/packages/laravilt/infolists)
[![Downloads](https://poser.pugx.org/laravilt/infolists/d/total.svg)](https://packagist.org/packages/laravilt/infolists)
[![Dependabot Updates](https://github.com/laravilt/infolists/actions/workflows/dependabot/dependabot-updates/badge.svg)](https://github.com/laravilt/infolists/actions/workflows/dependabot/dependabot-updates)
[![PHP Code Styling](https://github.com/laravilt/infolists/actions/workflows/fix-php-code-styling.yml/badge.svg)](https://github.com/laravilt/infolists/actions/workflows/fix-php-code-styling.yml)
[![Tests](https://github.com/laravilt/infolists/actions/workflows/tests.yml/badge.svg)](https://github.com/laravilt/infolists/actions/workflows/tests.yml)

Complete information display system with entries, sections, and flexible layouts for Laravilt. Display data in organized, readable formats with text, images, badges, icons, and lists.

## Features

- 📋 **Entry Types** - Text, Image, Badge, Icon, List entries
- 🎨 **Layouts** - Sections, grids, columns with collapsible support
- 🔤 **Formatting** - Date/time, money, number formatting
- 📱 **Responsive** - Mobile-friendly responsive layouts
- ⚡ **Inertia Integration** - Seamless Vue 3 integration

## Installation

```bash
composer require laravilt/infolists
```

## Quick Start

```php
use Laravilt\Infolists\Entries\TextEntry;
use Laravilt\Infolists\Entries\BadgeEntry;
use Laravilt\Schemas\Components\Section;

Section::make('Profile Information')
    ->schema([
        TextEntry::make('name')
            ->label('Full Name'),

        TextEntry::make('email')
            ->label('Email Address'),

        BadgeEntry::make('status')
            ->colors([
                'active' => 'success',
                'inactive' => 'secondary',
            ]),

        TextEntry::make('created_at')
            ->label('Joined')
            ->date('F j, Y'),
    ]);
```

## Generator Command

```bash
# Generate an infolist class
php artisan make:infolist UserInfolist
```

## Documentation

- **[Complete Documentation](docs/index.md)** - All entry types and formatting options
- **[MCP Server Guide](docs/mcp-server.md)** - AI agent integration

## Entry Types

- **TextEntry** - Display text with formatting
- **ImageEntry** - Display images
- **BadgeEntry** - Display badges with colors
- **IconEntry** - Display icons
- **ListEntry** - Display arrays/collections

## Formatting

- Date/time formatting
- Money formatting
- Number formatting
- Badge colors
- Icon colors
- Custom formatting

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
