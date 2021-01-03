<?php

/**
 * @noinspection UnknownInspectionInspection
 * @noinspection PhpUnused
 */

use October\Rain\Database\Updates\Migration;
use October\Rain\Support\Facades\DbDongle;

/**
 * This migration addresses a MySQL specific issue around STRICT MODE.
 * In past versions, Laravel would give timestamps a bad default value
 * of "0" considered invalid by MySQL. Strict mode is disabled and the
 * the timestamps are patched up. Credit for this work: Dave Shoreman.
 */
class FixSystemTimestamps extends Migration
{
    public function up(): void
    {
        DbDongle::disableStrictMode();

        foreach ($this->getCoreTables() as $table => $columns) {
            if (is_int($table)) {
                $table   = $columns;
                $columns = ['created_at', 'updated_at'];
            }

            DbDongle::convertTimestamps($table, $columns);
        }
    }

    public function down(): void
    {
        // ...
    }

    protected function getCoreTables(): array
    {
        return [
            'deferred_bindings',
            'system_files',
            'system_plugin_history'  => 'created_at',
            'system_plugin_versions' => 'created_at',
            'system_revisions',
        ];
    }
}
