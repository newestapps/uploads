<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreatePackageTable extends Migration
{

    private $table;

    /**
     * CreatePackageTable constructor.
     */
    public function __construct()
    {
        $this->table = 'file_uploads';
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function (Blueprint $table) {
                $table->increments('id');
                $table->nullableMorphs('uploaded_by');
                $table->nullableMorphs('owner');
                $table->bigInteger('size');
                $table->string('real_name');
                $table->string('stored_name');
                $table->string('strategy');
                $table->string('path');
                $table->string('mimes');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}