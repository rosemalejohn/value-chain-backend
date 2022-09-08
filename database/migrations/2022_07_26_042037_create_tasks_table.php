<?php

use App\Enums\TaskAssignmentRole;
use App\Enums\TaskStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('initiator_id')->constrained('users')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('outcome')->nullable();
            $table->tinyInteger('priority')->nullable();
            $table->tinyInteger('impact')->nullable();
            $table->tinyInteger('status')->default(TaskStatus::Pending->value);
            $table->tinyInteger('step')->nullable();
            $table->integer('order');
            $table->dateTime('due_date')->nullable();
            $table->float('estimate')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
        });

        Schema::create('task_members', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('task_id')->constrained('tasks');
            $table->tinyInteger('role')->default(TaskAssignmentRole::Contributor->value);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_members');
        Schema::dropIfExists('tasks');
    }
};
