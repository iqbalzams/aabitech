<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tool_related_tools', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tool_id')
                ->constrained('tools')
                ->cascadeOnDelete();

            $table->foreignId('related_tool_id')
                ->constrained('tools')
                ->cascadeOnDelete();

            $table->unsignedInteger('sort_order')
                ->default(10);

            $table->timestamps();

            $table->unique(
                ['tool_id', 'related_tool_id'],
                'tool_related_tools_unique'
            );

            $table->index(
                ['tool_id', 'sort_order'],
                'tool_related_tools_tool_sort_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_related_tools');
    }
};