<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('school_subscriptions', 'amount_paid')) {
                $table->decimal('amount_paid', 12, 2)->default(0)->after('status');
            }

            if (!Schema::hasColumn('school_subscriptions', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('amount_paid');
            }

            if (!Schema::hasColumn('school_subscriptions', 'paid_at')) {
                $table->date('paid_at')->nullable()->after('payment_reference');
            }
        });

        Schema::table('user_subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('user_subscriptions', 'amount_paid')) {
                $table->decimal('amount_paid', 12, 2)->default(0)->after('status');
            }

            if (!Schema::hasColumn('user_subscriptions', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('amount_paid');
            }

            if (!Schema::hasColumn('user_subscriptions', 'paid_at')) {
                $table->date('paid_at')->nullable()->after('payment_reference');
            }
        });
    }

    public function down(): void
    {
        Schema::table('school_subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'amount_paid',
                'payment_reference',
                'paid_at',
            ]);
        });

        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'amount_paid',
                'payment_reference',
                'paid_at',
            ]);
        });
    }
};