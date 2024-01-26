<?php

use H4zz4rdDev\Seat\SeatBuyback\Models\BuybackContract;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Seat\Web\Models\User;

class AlterBuybackContractToUserId extends Migration
{
    public function up(): void
    {
        Schema::table('buyback_contracts', function (Blueprint $table) {
            if (Schema::hasColumn('buyback_contracts', 'issuer_id')) {
                $table->dropForeign('buyback_contracts_issuer_id_foreign');
                $table->dropColumn('issuer_id');
            }
        });

        Schema::table('buyback_contracts', function (Blueprint $table) {
            $table->integer('issuer_id')->unsigned()->nullable();

            $table->foreign('issuer_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        $contracts = BuybackContract::whereNull('issuer_id')->get();

        foreach ($contracts as $contract) {
            $user = User::where('name', $contract->contractIssuer)->first();

            if ($user) {
                $contract->issuer_id = $user->id;
                $contract->save();
            }
        }

        BuybackContract::whereNull('issuer_id')->delete();

        Schema::table('buyback_contracts', function (Blueprint $table) {
            $table->dropColumn('contractIssuer');
        });
    }

    public function down(): void
    {
        Schema::table('buyback_contracts', function (Blueprint $table) {
            $table->string('contractIssuer')->nullable();
        });

        $contracts = BuybackContract::all();

        foreach ($contracts as $contract) {
            $user = User::find($contract->issuer_id)->first();

            if ($user) {
                $contract->contractIssuer = $user->name;
                $contract->save();
            }
        }

        BuybackContract::whereNull('contractIssuer')->delete();

        Schema::table('buyback_contracts', function (Blueprint $table) {
            $table->dropForeign('buyback_contracts_issuer_id_foreign');
            $table->dropColumn('issuer_id');
        });
    }
}