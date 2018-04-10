<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateUploadsTable extends Migration
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
                $table->string('real_name', 50);
                $table->string('stored_name');
                $table->string('strategy');
                $table->string('path');
                $table->string('mimes', 40);
                $table->string('extension', 10);
                $table->string('url', 255);
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
