<?php

/**
 * @noinspection UnknownInspectionInspection
 * @noinspection AutoloadingIssuesInspection
 * @noinspection PhpUnused
 */

namespace GromIT\Tenancy\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use October\Rain\Support\Facades\Schema;

class CreateTenantablesTable extends Migration
{
    public function up(): void
    {
        Schema::create('gromit_tenancy_tenantables', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->unsignedInteger('tenant_id');
            $table->foreign('tenant_id')
                ->references('id')
                ->on('gromit_tenancy_tenants')
                ->onDelete('cascade');

            $table->nullableMorphs('tenantable', 'tenantable_morph');

            $table->unique(['tenant_id', 'tenantable_type', 'tenantable_id'], 'tenantable_unique_model');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('gromit_tenancy_tenantables')) {
            Schema::table('gromit_tenancy_tenantables', function (Blueprint $table) {
                $table->dropForeign('gromit_tenancy_tenantables_tenant_id_foreign');
            });
        }

        Schema::dropIfExists('gromit_tenancy_tenantables');
    }
}
