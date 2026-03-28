<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MigrationGenerator
{
    public function generate($name, $columns = [])
    {
        
        $nameLower = Str::lower($name);
        $plural = Str::plural($nameLower);

        $timestamp = date('Y_m_d_His');

        $migrationName = "{$timestamp}_create_{$plural}_table.php";

        $migrationPath = database_path("migrations/{$migrationName}");

       
        $existing = glob(database_path("migrations/*_create_{$plural}_table.php"));
        if (!empty($existing)) {
            return [
                'status' => 'exists',
                'message' => "Migration already exists for {$name} at " . $existing[0],
            ];
        }

        $columnsCode = "";
        foreach ($columns as $col) {
            $columnsCode .= "\$table->{$col['type']}('{$col['name']}');\n            ";
        }

        $content = "<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('{$plural}', function (Blueprint \$table) {
            \$table->id();
            {$columnsCode}
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{$plural}');
    }
};
";

        file_put_contents($migrationPath, $content);
        return [
            'status' => 'created',
            'message' => "Migration for {$name} created at:\n".$migrationPath,
        ];
    }
}