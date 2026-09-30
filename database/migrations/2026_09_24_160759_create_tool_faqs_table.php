<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tool_faqs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tool_id')
                ->constrained('tools')
                ->cascadeOnDelete();

            /*
             * FAQ question displayed to the user.
             */
            $table->string('question');

            /*
             * FAQ answer.
             */
            $table->longText('answer');

            /*
             * Controls FAQ ordering on the page.
             */
            $table->unsignedInteger('sort_order')->default(0);

            /*
             * Allows individual FAQs to be disabled.
             */
            $table->boolean('status')->default(true);

            $table->timestamps();

            $table->index(
                ['tool_id', 'status', 'sort_order'],
                'tool_faqs_tool_status_order_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_faqs');
    }
};