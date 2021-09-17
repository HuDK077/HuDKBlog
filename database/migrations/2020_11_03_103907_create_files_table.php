<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_id')->nullable()->default('')->index('file_id')->comment('文件ID 文件的md5');
            $table->string('mime_type', 20)->nullable()->comment('文件类型');
            $table->text('size')->nullable()->comment('文件大小');
            $table->text('file_name')->nullable()->comment('文件名称');
            $table->text('client_file_name')->nullable()->comment('客户上传文件名');
            $table->text('file_path')->nullable()->comment('文件路径');
            $table->string('disk', 200)->nullable()->comment('存储器');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
