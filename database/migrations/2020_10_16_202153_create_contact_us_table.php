<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContactUsTable extends Migration {

	public function up()
	{
		Schema::create('contact_us', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('email');
			$table->string('title');
			$table->text('message');
			$table->string('name');
			$table->string('phone');
		});
	}

	public function down()
	{
		Schema::drop('contact_us');
	}
}