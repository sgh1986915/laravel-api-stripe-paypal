<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('firstname');
			$table->string('lastname');
			$table->string('username')->unique();
			$table->string('hashed_password');
			$table->string('password');
			$table->string('email')->unique();
			$table->string('organization');
			$table->string('status');
			$table->string('activation');
			$table->string('remember_token', 100)->nullable();
			$table->timestamps();
		});

		/*Schema::table('users', function($table)
		{
			$table->increments('id');
			$table->string('firstname');
			$table->string('lastname');
			$table->string('username')->unique();
			$table->string('password');
			$table->string('hashed_password');
			$table->string('email')->unique();
			$table->string('organization');
			$table->string('status');
			$table->string('activation');
			$table->string('remember_token', 100)->nullable();
			$table->timestamps();
		});*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
