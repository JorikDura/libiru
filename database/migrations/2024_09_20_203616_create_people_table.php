<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('russian_name');
            $table->text('description')->nullable();
            $table->text('russian_description')->nullable();
            $table->date('birth_date');
            $table->date('death_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
