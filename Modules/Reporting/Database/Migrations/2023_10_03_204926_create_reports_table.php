<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->nullable();
            $table->string('reporter')->default('noname');
            $table->string('reporter_nis')->nullable();
            $table->string('reporter_type')->default('other');
            $table->string('reporter_email')->nullable();
            $table->string('title');
            $table->text('content');
            $table->string('category')->nullable();
            $table->string('status')->default(0);
            $table->boolean('is_read')->default(0);
            $table->boolean('is_public')->default(0);
            $table->bigInteger('student_id')->default(0);
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
