<?php

/**
 * @noinspection UnknownInspectionInspection
 * @noinspection AutoloadingIssuesInspection
 * @noinspection PhpUnused
 */

namespace SergeyKasyanov\Tenancy\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use October\Rain\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    public function up(): void
    {
        Schema::create('sergeykasyanov_tenancy_tenants', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->string('name');
            $table->boolean('is_active')->default(false);
            $table->string('database_name');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sergeykasyanov_tenancy_tenants');
    }
}
