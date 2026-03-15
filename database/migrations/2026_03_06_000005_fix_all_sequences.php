<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            // Tables that need sequence fix
            $tables = [
                'posts',
                'post_meta',
                'term_relationships',
                'media',
                'users',
                'terms',
                'taxonomies',
            ];

            foreach ($tables as $table) {
                $sequenceName = $table . '_id_seq';
                
                try {
                    // Check if table exists
                    $tableExists = DB::select("SELECT EXISTS (SELECT 1 FROM information_schema.tables WHERE table_name = '{$table}')");
                    
                    if (!$tableExists[0]->exists) {
                        echo "Table {$table} does not exist, skipping...\n";
                        continue;
                    }
                    
                    // Check if sequence exists
                    $sequenceExists = DB::select("SELECT EXISTS (SELECT 1 FROM pg_class WHERE relname = '{$sequenceName}' AND relkind = 'S')");
                    
                    if (!$sequenceExists[0]->exists) {
                        // Create the sequence
                        DB::statement("CREATE SEQUENCE {$sequenceName}");
                        echo "Created sequence {$sequenceName}\n";
                        
                        // Set the sequence owner to the id column
                        DB::statement("ALTER SEQUENCE {$sequenceName} OWNED BY {$table}.id");
                        
                        // Set the default value for id column to use the sequence
                        DB::statement("ALTER TABLE {$table} ALTER COLUMN id SET DEFAULT nextval('{$sequenceName}')");
                    }
                    
                    // Reset the sequence to the correct value
                    DB::statement("SELECT setval('{$sequenceName}', (SELECT COALESCE(MAX(id), 0) + 1 FROM {$table}), false)");
                    echo "Fixed sequence for {$table}\n";
                    
                } catch (\Exception $e) {
                    echo "Error fixing sequence for {$table}: {$e->getMessage()}\n";
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse
    }
};
