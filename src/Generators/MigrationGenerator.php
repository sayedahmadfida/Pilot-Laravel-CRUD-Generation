<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Str;

class MigrationGenerator
{
    public function generate($name)
    {
        
        $nameLower = Str::lower($name);
        $plural = Str::plural($nameLower);

        $timestamp = date('Y_m_d_His');

        $migrationName = "{$timestamp}_create_{$plural}_table.php";

        $migrationPath = database_path("migrations/{$migrationName}");

        if (file_exists($migrationPath)) {
            return;
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
    }
}