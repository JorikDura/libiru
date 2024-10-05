<?php

declare(strict_types=1);

use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('person_subscribers', function (Blueprint $table) {
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Person::class);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('person_subscribers');
    }
};
