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
        Schema::table('publishers', function (Blueprint $table) {

            $table->string('slug')
                ->nullable()
                ->after('name');

            $table->text('address')
                ->nullable()
                ->after('phone');

            $table->boolean('is_active')
                ->default(true)
                ->after('address');

            $table->softDeletes();

        });

        DB::table('publishers')
            ->orderBy('id')
            ->get()
            ->each(function ($publisher) {

                DB::table('publishers')
                    ->where('id', $publisher->id)
                    ->update([
                        'slug' => Str::slug($publisher->name . '-' . $publisher->id),
                        'is_active' => true,
                    ]);

            });

        Schema::table('publishers', function (Blueprint $table) {

            $table->string('slug')
                ->nullable(false)
                ->change();

            $table->unique('slug');

        });
    }

    public function down(): void
    {
        Schema::table('publishers', function (Blueprint $table) {

            $table->dropUnique(['slug']);

            $table->dropColumn([
                'slug',
                'address',
                'is_active',
            ]);

            $table->dropSoftDeletes();

        });
    }
};