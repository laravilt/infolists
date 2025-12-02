<?php

namespace Laravilt\Infolists\Mcp\Tools;

use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class GenerateInfolistTool extends Tool
{
    protected string $description = 'Generate a new infolist class for displaying information';

    public function handle(Request $request): Response
    {
        $name = $request->string('name');

        $command = 'php '.base_path('artisan').' make:infolist "'.$name.'" --no-interaction';

        if ($request->boolean('force', false)) {
            $command .= ' --force';
        }

        exec($command, $output, $returnCode);

        if ($returnCode === 0) {
            $response = "✅ Infolist '{$name}' created successfully!\n\n";
            $response .= "📖 Location: app/Infolists/{$name}.php\n\n";
            $response .= "📦 Available entry types: TextEntry, ImageEntry, BadgeEntry, IconEntry, ListEntry\n";

            return Response::text($response);
        } else {
            return Response::text('❌ Failed to create infolist: '.implode("\n", $output));
        }
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()
                ->description('Infolist class name in StudlyCase (e.g., "UserInfolist")')
                ->required(),
            'force' => $schema->boolean()
                ->description('Overwrite existing file')
                ->default(false),
        ];
    }
}
