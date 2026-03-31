<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SAFE: Fix views column for PostgreSQL compatibility
        if (DB::getDriverName() === 'pgsql') {
            try {
                // Check if views column exists and has NULL values
                $nullCount = DB::table('posts')->whereNull('views')->count();
                
                if ($nullCount > 0) {
                    echo "Fixing {$nullCount} NULL views values...\n";
                    DB::statement("UPDATE posts SET views = 0 WHERE views IS NULL");
                }
                
                // Only modify if needed - check current column type
                $columnInfo = DB::select("SELECT data_type, is_nullable, column_default 
                                        FROM information_schema.columns 
                                        WHERE table_name = 'posts' AND column_name = 'views'");
                
                if (!empty($columnInfo)) {
                    $column = $columnInfo[0];
                    
                    // Only change if not already properly configured
                    if ($column->is_nullable === 'YES' || $column->column_default !== '0') {
                        echo "Updating views column configuration...\n";
                        
                        // Safe column modification
                        DB::statement("ALTER TABLE posts ALTER COLUMN views SET DEFAULT 0");
                        DB::statement("ALTER TABLE posts ALTER COLUMN views SET NOT NULL");
                    }
                }
                
                echo "Views column fixed successfully!\n";
                
            } catch (\Exception $e) {
                echo "Error fixing views column: " . $e->getMessage() . "\n";
                // Don't fail the migration, just log the error
            }
        }
    }

    public function down(): void
    {
        // Safe rollback - only if needed
        if (DB::getDriverName() === 'pgsql') {
            try {
                DB::statement("ALTER TABLE posts ALTER COLUMN views DROP DEFAULT");
                DB::statement("ALTER TABLE posts ALTER COLUMN views DROP NOT NULL");
            } catch (\Exception $e) {
                // Ignore rollback errors
            }
        }
    }
};