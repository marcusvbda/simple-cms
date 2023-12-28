<?php

use database\migrations\DefaultMigration;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\StartUpSeeder;

return new class extends DefaultMigration
{
	public function up()
	{
		$this->initFramework();
		(new StartUpSeeder())->run();
		$aclSeeder = new PermissionSeeder();
		$aclSeeder->makePermissions('Permissões', 'permissions', [PermissionSeeder::VIEWLIST]);
		$aclSeeder->makePermissions('Grupos de Acesso', 'access-groups');
		$aclSeeder->makePermissions('Usuários', 'users');
	}

	public function down()
	{
		$this->dropAllTables();
	}
};
