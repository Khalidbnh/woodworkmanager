<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('project_material', function (Blueprint $table) {
            $table->dropColumn([
                'amount_paid',
                'amount_remaining',
                'payment_status',
                'purchase_date',
                'paid_date',
                'notes',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_material', function (Blueprint $table) {
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->decimal('amount_remaining', 10, 2)->default(0);
            $table->string('payment_status')->default('unpaid');
            $table->date('purchase_date')->nullable();
            $table->date('paid_date')->nullable();
            $table->text('notes')->nullable();
        });
    }
};
