<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tool_seo_sections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tool_id')
                ->constrained('tools')
                ->cascadeOnDelete();

            /*
             * Internal identifier used by the dynamic tool page.
             *
             * Examples:
             * introduction
             * how_to_use
             * features
             * privacy
             * use_cases
             * additional_information
             */
            $table->string('section_key', 80);

            /*
             * Visible H2/H3 heading.
             */
            $table->string('heading');

            /*
             * SEO/editorial content.
             *
             * HTML is allowed here because the content may contain:
             * paragraphs, lists, links, emphasis, etc.
             */
            $table->longText('content');

            /*
             * Controls the order in which sections appear.
             */
            $table->unsignedInteger('sort_order')->default(0);

            /*
             * Allows a section to be temporarily disabled
             * without deleting its content.
             */
            $table->boolean('status')->default(true);

            $table->timestamps();

            /*
             * One section of a particular type per tool.
             *
             * Example:
             * JSON Formatter + introduction = one record.
             */
            $table->unique(
                ['tool_id', 'section_key'],
                'tool_seo_sections_tool_section_unique'
            );

            $table->index(
                ['tool_id', 'status', 'sort_order'],
                'tool_seo_sections_tool_status_order_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_seo_sections');
    }
};