# MCP Server Integration

The Laravilt Infolists package can be integrated with MCP (Model Context Protocol) server for AI agent interaction.

## Available Generator Command

### make:infolist
Generate a new infolist class.

**Usage:**
```bash
php artisan make:infolist UserInfolist
php artisan make:infolist Admin/UserInfolist
php artisan make:infolist UserInfolist --force
```

**Arguments:**
- `name` (string, required): Infolist class name (StudlyCase)

**Options:**
- `--force`: Overwrite existing file

**Generated Structure:**
```php
<?php

namespace App\Infolists;

use Laravilt\Infolists\Infolist;
use Laravilt\Infolists\Entries\TextEntry;
use Laravilt\Infolists\Entries\ImageEntry;
use Laravilt\Schemas\Components\Section;

class UserInfolist
{
    public static function make(): Infolist
    {
        return Infolist::make()
            ->schema([
                Section::make('Information')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Name'),

                        TextEntry::make('email')
                            ->label('Email'),

                        TextEntry::make('created_at')
                            ->label('Created')
                            ->date(),
                    ]),
            ]);
    }
}
```

## Entry Types Reference

For MCP tools to provide entry type information:

- **TextEntry**: Display text with formatting (date, money, numeric)
- **ImageEntry**: Display images with various sizes and shapes
- **BadgeEntry**: Display status badges with colors
- **IconEntry**: Display icons with colors
- **ListEntry**: Display arrays or collections as lists

## Integration Example

MCP server tools should provide:

1. **list-infolists** - List all infolist classes in the application
2. **infolist-info** - Get details about a specific infolist class
3. **generate-infolist** - Generate a new infolist class with specified entries
4. **list-entry-types** - List all available entry types

## Security

The MCP server runs with the same permissions as your Laravel application. Ensure:
- Proper file permissions on the app/Infolists directory
- Secure configuration of the MCP server
- Limited access to the MCP configuration file
