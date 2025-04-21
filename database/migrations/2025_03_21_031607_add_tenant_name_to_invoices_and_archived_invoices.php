<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('name')->after('category_id');
        });

        Schema::table('archived_invoices', function (Blueprint $table) {
            $table->string('name')->after('category_id');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        Schema::table('archived_invoices', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};
