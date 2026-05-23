<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (Schema::hasColumn('books', 'school_id')) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            }

            if (! Schema::hasColumn('books', 'slug')) {
                $table->string('slug')->nullable()->after('title');
            }
        });

        DB::table('books')->orderBy('id')->get()->each(function ($book) {
            DB::table('books')
                ->where('id', $book->id)
                ->update([
                    'slug' => Str::slug($book->title . '-' . $book->id),
                ]);
        });

        Schema::table('books', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();

            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};