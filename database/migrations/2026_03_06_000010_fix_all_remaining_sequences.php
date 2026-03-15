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
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        $tables = [
            'users',
            'user_meta',
            'roles',
            'permissions',
            'action_logs',
            'notifications',
            'email_logs',
            'email_templates',
            'email_connections',
            'advertisements',
            'ad_clicks',
            'ad_impressions',
            'billing_transactions',
            'wallet_transactions',
            'modules',
            'settings',
            'general_settings',
        ];

        foreach ($tables as $table) {
            try {
                // Check if table exists
                $tableExists = DB::select("SELECT EXISTS (SELECT 1 FROM information_schema.tables WHERE table_name = '{$table}')");
                
                if (!$tableExists[0]->exists) {
                    echo "Table {$table} does not exist, skipping...\n";
                    continue;
                }

                // Delete NULL id records
                $deleted = DB::delete("DELETE FROM {$table} WHERE id IS NULL");
                if ($deleted > 0) {
                    echo "Deleted {$deleted} records with NULL id from {$table}\n";
                }

                // Check if sequence exists
                $sequenceName = "{$table}_id_seq";
                $sequenceExists = DB::select("SELECT EXISTS (SELECT 1 FROM pg_class WHERE relname = '{$sequenceName}' AND relkind = 'S')");
                
                if (!$sequenceExists[0]->exists) {
                    // Create the sequence
                    DB::statement("CREATE SEQUENCE {$sequenceName}");
                    echo "Created {$sequenceName}\n";
                }

                // Make sure id column is set to NOT NULL and has default
                DB::statement("ALTER TABLE {$table} ALTER COLUMN id SET NOT NULL");
                DB::statement("ALTER TABLE {$table} ALTER COLUMN id SET DEFAULT nextval('{$sequenceName}')");

                // Set the sequence owner
                DB::statement("ALTER SEQUENCE {$sequenceName} OWNED BY {$table}.id");

                // Reset the sequence to the correct value
                $maxId = DB::table($table)->max('id') ?? 0;
                DB::statement("SELECT setval('{$sequenceName}', " . ($maxId + 1) . ", false)");

                echo "Fixed {$table} table id column and sequence\n";
            } catch (\Exception $e) {
                echo "Error fixing {$table}: " . $e->getMessage() . "\n";
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reverse
    }
};
