<?php

namespace Database\Seeders;

use App\Http\Models\Permission;
use App\Http\Models\Tenant;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StartUpSeeder extends Seeder
{
	public Tenant $tenant;

	public function run()
	{
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		$this->createTenant();
		$this->createUsers();
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('COMMIT;');
	}

	public function createTenant()
	{
		$this->tenant = Tenant::create([
			'name' => 'Branco motores',
			'data' => []
		]);
	}

	protected function createUsers()
	{
		User::insert([
			"name" => "Root",
			"email" => "root@root.com",
			"role" =>  "root",
			"password" => bcrypt("roottoor"),
			"email_verified_at" => now(),
		]);
	}
}
