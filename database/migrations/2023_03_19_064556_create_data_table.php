<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('state',10)->nullable();
            $table->string('unique_id',100)->nullable();
            $table->string('device',100)->nullable();
            $table->string('unlock_status',50)->nullable();
            $table->text('notes')->nullable();
            $table->string('traking_number',100)->nullable();
            $table->string('attachment',100)->nullable();
            $table->integer('priority')->nullable();
            $table->string('requested_device',100)->nullable();
            $table->string('current_status',100)->nullable();
            $table->integer('music_count')->nullable();
            $table->string('received_via',10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data');
    }
};
