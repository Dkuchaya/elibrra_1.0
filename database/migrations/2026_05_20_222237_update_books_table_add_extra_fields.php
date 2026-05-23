<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'language')) {
                $table->string('language')->default('English')->after('published_year');
            }

            if (!Schema::hasColumn('books', 'file_size')) {
                $table->unsignedBigInteger('file_size')->nullable()->after('language');
            }

            if (!Schema::hasColumn('books', 'views')) {
                $table->unsignedBigInteger('views')->default(0)->after('file_size');
            }

            if (!Schema::hasColumn('books', 'featured')) {
                $table->boolean('featured')->default(false)->after('views');
            }

            if (!Schema::hasColumn('books', 'edition')) {
                $table->string('edition')->nullable()->after('featured');
            }

            if (!Schema::hasColumn('books', 'book_type')) {
                $table->enum('book_type', ['pdf', 'epub', 'audio'])->default('pdf')->after('edition');
            }

            if (!Schema::hasColumn('books', 'subscription_required')) {
                $table->boolean('subscription_required')->default(false)->after('book_type');
            }

            if (Schema::hasColumn('books', 'school_id')) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            }
        });

        // Make sure existing slugs are unique before adding unique index
        $books = DB::table('books')->select('id', 'slug', 'title')->orderBy('id')->get();

        foreach ($books as $book) {
            $baseSlug = $book->slug ?: \Illuminate\Support\Str::slug($book->title);
            $newSlug = $baseSlug;
            $count = 1;

            while (
                DB::table('books')
                    ->where('slug', $newSlug)
                    ->where('id', '!=', $book->id)
                    ->exists()
            ) {
                $newSlug = $baseSlug . '-' . $book->id . '-' . $count;
                $count++;
            }

            DB::table('books')->where('id', $book->id)->update([
                'slug' => $newSlug,
            ]);
        }

       
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropUnique(['slug']);

            $table->dropColumn([
                'language',
                'file_size',
                'views',
                'featured',
                'edition',
                'book_type',
                'subscription_required',
            ]);
        });
    }
};