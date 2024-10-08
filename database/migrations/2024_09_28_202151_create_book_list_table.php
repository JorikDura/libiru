<?php

declare(strict_types=1);

use App\Enums\BookListStatus;
use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('user_book_list', function (Blueprint $table) {
            $table->foreignIdFor(Book::class)->index();
            $table->foreignIdFor(User::class)->index();
            $table->enum('status', array_column(BookListStatus::cases(), 'value'))
                ->default(BookListStatus::PLANNED);
            $table->boolean('is_favorite')->default(false);
            $table->integer('score')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_book_list');
    }
};
