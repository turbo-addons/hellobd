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
        // Fix PostgreSQL sequence for questions table
        if (DB::getDriverName() === 'pgsql') {
            // First, check if sequence exists, if not create it
            $sequenceExists = DB::select("SELECT EXISTS (SELECT 1 FROM pg_class WHERE relname = 'questions_id_seq' AND relkind = 'S')");
            
            if (!$sequenceExists[0]->exists) {
                // Create the sequence
                DB::statement("CREATE SEQUENCE questions_id_seq");
                // Set the sequence owner to the id column
                DB::statement("ALTER SEQUENCE questions_id_seq OWNED BY questions.id");
                // Set the default value for id column to use the sequence
                DB::statement("ALTER TABLE questions ALTER COLUMN id SET DEFAULT nextval('questions_id_seq')");
            }
            
            // Reset the sequence to the correct value
            DB::statement("SELECT setval('questions_id_seq', (SELECT COALESCE(MAX(id), 0) + 1 FROM questions), false)");
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
