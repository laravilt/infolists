<?php

namespace Laravilt\Infolists\Mcp;

use Laravel\Mcp\Server;
use Laravilt\Infolists\Mcp\Tools\GenerateInfolistTool;
use Laravilt\Infolists\Mcp\Tools\SearchDocsTool;

class LaraviltInfolistsServer extends Server
{
    protected string $name = 'Laravilt Infolists';

    protected string $version = '1.0.0';

    protected string $instructions = <<<'MARKDOWN'
        This server provides information display capabilities for Laravilt projects.

        You can:
        - Generate new infolist classes
        - Search infolists documentation
        - Access information about entry types and formatting

        Infolists display information with text, image, badge, icon, and list entries.
    MARKDOWN;

    protected array $tools = [
        GenerateInfolistTool::class,
        SearchDocsTool::class,
    ];
}
