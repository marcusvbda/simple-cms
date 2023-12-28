<?php

use Illuminate\Database\Schema\Blueprint;
use database\migrations\DefaultMigration;
use Database\Seeders\PermissionSeeder;

return new class extends DefaultMigration
{
    public function up()
    {
        $this->createTable('interfaces', function (Blueprint $table) {
            $table->string('name');
            $table = $this->addForeignKey($table, 'tenant_id', 'tenants', 'id');
        });

        $this->createTable('integrators', function (Blueprint $table) {
            $table->string('name');
            $table->string('password');
            $table = $this->addForeignKey($table, 'tenant_id', 'tenants', 'id');
            $table = $this->addForeignKey($table, 'interface_id', 'interfaces', 'id');
        });

        $this->createTable('fields', function (Blueprint $table) {
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('slug');
            $table->string('type');
            $table->string('value')->nullable();
            $table->jsonb('valueList')->nullable();
            $table->boolean('boolValue')->default(false);
            $table = $this->addForeignKey($table, 'interface_id', 'interfaces', 'id');
            $table = $this->addForeignKey($table, 'tenant_id', 'tenants', 'id');
        });

        $aclSeeder = new PermissionSeeder();
        $aclSeeder->makePermissions('Campos', 'fields');
        $aclSeeder->makePermissions('Interfaces', 'interfaces');
        $aclSeeder->makePermissions('Integradores', 'integrators');
    }
};
