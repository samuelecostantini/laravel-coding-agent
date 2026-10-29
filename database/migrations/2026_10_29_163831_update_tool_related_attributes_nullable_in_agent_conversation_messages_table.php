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
        Schema::table('agent_conversation_messages', function (Blueprint $table) {
            $table->text('tool_calls')->nullable()->change();
            $table->text('tool_results')->nullable()->change();
            $table->text('usage')->nullable()->change();
            $table->text('meta')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agent_conversation_messages', function (Blueprint $table) {
            $table->text('tool_calls')->nullable(false)->change();
            $table->text('tool_results')->nullable(false)->change();
            $table->text('usage')->nullable(false)->change();
            $table->text('meta')->nullable(false)->change();
        });
    }
};
