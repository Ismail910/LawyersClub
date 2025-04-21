<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->string('tenant_name')->after('category_id');
        });
        Schema::table('archived_budgets', function (Blueprint $table) {
            $table->string('tenant_name')->after('category_id');

        });
    }

    public function down()
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropColumn('tenant_name');
        });
        Schema::table('archived_budgets', function (Blueprint $table) {
            $table->dropColumn('tenant_name');
        });
    }
};
