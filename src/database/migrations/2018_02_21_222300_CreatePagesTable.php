<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('pages.table'), function(Blueprint $table) {
           $table->increments('id');
           $table->string('code')->index();
           $table->string('title');
           $table->string('slug')->index()->unique();
           $table->text('body')->nullable();
           NestedSet::columns($table);
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('pages.table'));
    }
}
