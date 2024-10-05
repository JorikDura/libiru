<?php

declare(strict_types=1);

use App\Models\Book;
use App\Models\Genre;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('book_genre', function (Blueprint $table) {
            $table->foreignIdFor(Book::class)->index();
            $table->foreignIdFor(Genre::class)->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_genre');
    }
};
