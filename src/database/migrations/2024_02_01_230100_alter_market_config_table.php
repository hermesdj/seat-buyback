<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMarketConfigTable extends Migration
{
    public function up(): void
    {
        Schema::table('buyback_market_config', function (Blueprint $table) {
            if (Schema::hasColumn('buyback_market_config', 'typeName')) {
                $table->dropColumn('typeName');
            }
            if (Schema::hasColumn('buyback_market_config', 'groupId')) {
                $table->dropColumn('groupId');
            }
            if (Schema::hasColumn('buyback_market_config', 'groupName')) {
                $table->dropColumn('groupName');
            }

            $table->foreign('typeId')
                ->references('typeID')
                ->on('invTypes')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('buyback_market_config', function (Blueprint $table) {
            if (!Schema::hasColumn('buyback_market_config', 'typeName')) {
                $table->addColumn('string', 'typeName');
            }
            if (!Schema::hasColumn('buyback_market_config', 'groupId')) {
                $table->addColumn('int', 'groupId');
            }
            if (!Schema::hasColumn('buyback_market_config', 'groupName')) {
                $table->addColumn('string', 'groupName');
            }

            $table->dropForeign('buyback_market_config_typeId_foreign');
        });
    }
}
