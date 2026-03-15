<?php

namespace Fida\Crud\Generators;

class MigrationGenerator
{
    public function generate($name)
    {
        $table = strtolower($name) . 's';

        $timestamp = date('Y_m_d_His');

        $migrationName = "{$timestamp}_create_{$table}_table.php";

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
        Schema::create('{$table}', function (Blueprint \$table) {
            \$table->id();
            
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{$table}');
    }
};
";

        file_put_contents($migrationPath, $content);
    }
}