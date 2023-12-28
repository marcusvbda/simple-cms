<?php

namespace Database\Seeders;

use App\Http\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
	const VIEWLIST = ['viewlist', 'visualizar'];
	const CREATE = ['create', 'cadastrar'];
	const EDIT = ['edit', 'editar'];
	const DELETE = ['delete', 'excluir'];
	const COMPLETE_PERMISSIONS = [self::VIEWLIST, self::CREATE, self::EDIT, self::DELETE];

	public function makePermissions($type, $value, $permissions = self::COMPLETE_PERMISSIONS)
	{
		$createdPermissions = [];
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		foreach ($permissions as $permission) {
			$valueIndex = $permission[0];
			$valueTranslate = $permission[1];
			$permissionName = ucfirst(strtolower("$valueTranslate $type"));
			$permissionKey = "$valueIndex-$value";

			$createdPermissions[] = Permission::updateOrCreate(
				['type' => $type, 'key' => $permissionKey],
				['name' => $permissionName, 'type' => $type, 'key' => $permissionKey]
			);
		}
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('COMMIT;');
		return $createdPermissions;
	}
}
