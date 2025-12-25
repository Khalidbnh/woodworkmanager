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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')
                ->constrained()
                ->onDelete('cascade');

            $table->enum('type', ['devis', 'facture']);
            $table->string('invoice_number')->unique();
            $table->decimal('amount', 10, 2);

            $table->enum('status', [
                'draft',
                'sent',
                'accepted',
                'rejected',
                'paid',
                'unpaid',
                'overdue'
            ])->default('draft');

            $table->date('issued_date');
            $table->date('due_date')->nullable(); // For factures
            $table->date('valid_until')->nullable(); // For devis
            $table->date('paid_date')->nullable(); // For factures

            $table->text('notes')->nullable();

            // Link Devis → Facture
            $table->foreignId('converted_to_facture_id')
                ->nullable()
                ->constrained('invoices')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
