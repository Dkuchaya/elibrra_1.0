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
        Schema::table('authors', function (Blueprint $table) {

            $table->string('slug')
                ->nullable()
                ->after('name');

            $table->boolean('is_active')
                ->default(true)
                ->after('biography');

            $table->softDeletes();

        });

        DB::table('authors')
            ->orderBy('id')
            ->get()
            ->each(function ($author) {

                DB::table('authors')
                    ->where('id', $author->id)
                    ->update([
                        'slug' => Str::slug($author->name . '-' . $author->id),
                        'is_active' => true,
                    ]);

            });

        Schema::table('authors', function (Blueprint $table) {

            $table->string('slug')
                ->nullable(false)
                ->change();

            $table->unique('slug');

        });
    }

    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {

            $table->dropUnique(['slug']);

            $table->dropColumn([
                'slug',
                'is_active',
            ]);

            $table->dropSoftDeletes();

        });
    }
};