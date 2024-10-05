<?php

declare(strict_types=1);

use App\Models\Publisher;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Publisher::class)->index();
            $table->string('name')->nullable();
            $table->string('russian_name');
            $table->string('description')->nullable();
            $table->string('russian_description')->nullable();
            $table->date('publish_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
