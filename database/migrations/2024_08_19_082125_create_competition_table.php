<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('template')->default('DEFAULT');
            $table->dateTime('start')->comment('start datetime of the competition');
            $table->dateTime('end')->comment('end datetime of the competition');
            $table->string('special_offer')->nullable();
            $table->integer('entries_warning')->nullable();
            $table->integer('max_entries')->default(-1)->comment('number of times a tel number can enter the competition');
            $table->integer('max_paid_entries');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
};
