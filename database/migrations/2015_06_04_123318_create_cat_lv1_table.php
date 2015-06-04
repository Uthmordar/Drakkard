<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatLv1Table extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('categories', function(Blueprint $table){
            $table->increments('id');
            $table->text('name');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('children_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::drop('categories');
    }
}
