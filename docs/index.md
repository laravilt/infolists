# Laravilt Infolists Documentation

Complete information display system with entries, sections, and flexible layouts for Laravilt.

## Table of Contents

1. [Getting Started](#getting-started)
2. [Architecture](#architecture)
3. [Infolist Generation](#infolist-generation)
4. [Entry Types](#entry-types)
5. [API Reference](#api-reference)
6. [MCP Server Integration](mcp-server.md)

## Overview

Laravilt Infolists provides a comprehensive system for displaying information with:

- **Multiple Entry Types**: Text, image, badge, icon, and more
- **Flexible Layouts**: Sections, grids, and columns
- **Rich Formatting**: Dates, money, badges, colors
- **Sections**: Collapsible sections with headers and icons
- **Responsive**: Mobile-friendly responsive layouts
- **Inertia Integration**: Seamless Vue 3 integration

## Quick Start

```bash
# Generate a new infolist class
php artisan make:infolist UserInfolist

# Use in your controller
use App\Infolists\UserInfolist;

$infolist = UserInfolist::make()
    ->record($user);

return Inertia::render('Users/Show', [
    'infolist' => $infolist->toInertiaProps(),
]);
```

## Key Features

### 📋 Entry Types
- **TextEntry**: Display text with formatting
- **ImageEntry**: Display images
- **BadgeEntry**: Display badges with colors
- **IconEntry**: Display icons
- **ListEntry**: Display arrays/collections

### 🎨 Layouts
- Sections with headers/footers
- Grid layouts
- Column layouts
- Collapsible sections
- Inline entries

### 🔤 Formatting
- Date/time formatting
- Money formatting
- Number formatting
- Badge colors
- Icon colors
- Custom formatting

## System Requirements

- PHP 8.3+
- Laravel 12+
- Inertia.js v2+
- Vue 3

## Installation

```bash
composer require laravilt/infolists
```

The service provider is auto-discovered and will register automatically.

## Basic Usage

### Creating an Infolist Class

```php
<?php

namespace App\Infolists;

use Laravilt\Infolists\Infolist;
use Laravilt\Infolists\Entries\TextEntry;
use Laravilt\Infolists\Entries\ImageEntry;
use Laravilt\Infolists\Entries\BadgeEntry;
use Laravilt\Schemas\Components\Section;

class UserInfolist
{
    public static function make(): Infolist
    {
        return Infolist::make()
            ->schema([
                Section::make('Profile Information')
                    ->description('User profile details')
                    ->icon('user')
                    ->schema([
                        ImageEntry::make('avatar')
                            ->label('Avatar')
                            ->circular()
                            ->size(64),

                        TextEntry::make('name')
                            ->label('Full Name'),

                        TextEntry::make('email')
                            ->label('Email Address'),

                        BadgeEntry::make('status')
                            ->label('Status')
                            ->colors([
                                'active' => 'success',
                                'inactive' => 'secondary',
                                'suspended' => 'danger',
                            ]),

                        TextEntry::make('created_at')
                            ->label('Joined')
                            ->date('F j, Y'),
                    ]),

                Section::make('Account Details')
                    ->icon('info')
                    ->schema([
                        TextEntry::make('role')
                            ->label('Role'),

                        TextEntry::make('last_login_at')
                            ->label('Last Login')
                            ->dateTime(),

                        TextEntry::make('posts_count')
                            ->label('Total Posts')
                            ->numeric(),
                    ]),
            ]);
    }
}
```

### Using in a Controller

```php
use App\Models\User;
use App\Infolists\UserInfolist;
use Inertia\Inertia;

public function show(User $user)
{
    $infolist = UserInfolist::make()
        ->record($user);

    return Inertia::render('Users/Show', [
        'infolist' => $infolist->toInertiaProps(),
    ]);
}
```

### Using in Vue

```vue
<template>
  <div>
    <Infolist :data="infolist" />
  </div>
</template>

<script setup>
import Infolist from '@/components/infolists/Infolist.vue'

const props = defineProps({
  infolist: Object
})
</script>
```

## Entry Types Reference

### TextEntry

```php
TextEntry::make('name')
    ->label('Name')
    ->default('N/A')
    ->color('primary')
    ->weight('bold')
    ->size('lg');
```

### ImageEntry

```php
ImageEntry::make('avatar')
    ->label('Avatar')
    ->circular()
    ->size(64)
    ->defaultImageUrl('/images/default-avatar.png');
```

### BadgeEntry

```php
BadgeEntry::make('status')
    ->label('Status')
    ->colors([
        'active' => 'success',
        'pending' => 'warning',
        'inactive' => 'secondary',
    ]);
```

### IconEntry

```php
IconEntry::make('type')
    ->label('Type')
    ->icons([
        'premium' => 'star',
        'standard' => 'circle',
    ])
    ->colors([
        'premium' => 'primary',
        'standard' => 'secondary',
    ])
    ->size(24);
```

### ListEntry

```php
ListEntry::make('tags')
    ->label('Tags')
    ->bulleted()
    ->limit(5);
```

## Formatting

### Date Formatting

```php
TextEntry::make('created_at')
    ->date('F j, Y'); // January 1, 2024

TextEntry::make('updated_at')
    ->dateTime('F j, Y g:i A'); // January 1, 2024 3:45 PM

TextEntry::make('deleted_at')
    ->since(); // 2 hours ago
```

### Money Formatting

```php
TextEntry::make('price')
    ->money('USD'); // $1,234.56

TextEntry::make('balance')
    ->money('EUR', divideBy: 100); // €12.35
```

### Number Formatting

```php
TextEntry::make('views')
    ->numeric(decimalPlaces: 0); // 1,234

TextEntry::make('rating')
    ->numeric(decimalPlaces: 2); // 4.50
```

## Generator Command

```bash
# Generate an infolist class
php artisan make:infolist UserInfolist

# Force overwrite existing file
php artisan make:infolist UserInfolist --force
```

## Best Practices

1. **Use Infolist Classes**: Create dedicated infolist classes for reusability
2. **Group Related Information**: Use sections to organize related data
3. **Use Appropriate Entry Types**: Choose the right entry type for better presentation
4. **Add Labels**: Always provide clear labels for entries
5. **Format Data**: Use formatting methods for dates, money, etc.
6. **Provide Defaults**: Show meaningful defaults for empty values
7. **Use Icons**: Add icons to sections for visual hierarchy

## Examples

### Product Infolist

```php
class ProductInfolist
{
    public static function make(): Infolist
    {
        return Infolist::make()
            ->schema([
                Section::make('Product Details')
                    ->icon('package')
                    ->schema([
                        ImageEntry::make('image')
                            ->size(128),

                        TextEntry::make('name'),

                        TextEntry::make('description')
                            ->columnSpan(2),

                        BadgeEntry::make('status')
                            ->colors([
                                'active' => 'success',
                                'draft' => 'secondary',
                                'archived' => 'danger',
                            ]),
                    ]),

                Section::make('Pricing')
                    ->icon('dollar-sign')
                    ->schema([
                        TextEntry::make('price')
                            ->money('USD'),

                        TextEntry::make('compare_at_price')
                            ->money('USD'),

                        TextEntry::make('cost')
                            ->money('USD'),
                    ]),

                Section::make('Inventory')
                    ->icon('box')
                    ->schema([
                        TextEntry::make('quantity')
                            ->numeric(),

                        TextEntry::make('sku'),

                        BadgeEntry::make('stock_status')
                            ->colors([
                                'in_stock' => 'success',
                                'low_stock' => 'warning',
                                'out_of_stock' => 'danger',
                            ]),
                    ]),
            ]);
    }
}
```

## Support

- GitHub Issues: github.com/laravilt/infolists
- Documentation: docs.laravilt.com
- Discord: discord.laravilt.com
