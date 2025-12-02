<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->renameColumn('sms_offers_enabled', 'sms_offer_enabled');
            $table->renameColumn('sms_message', 'sms_offer_message');
            $table->boolean('sms_first_entry_enabled')->default(false)->after('sms_offer_message')->comment('send a sms to a successful entrant after their first entry.');
            $table->string('sms_first_entry_message', 160)->nullable()->after('sms_first_entry_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->renameColumn('sms_offer_enabled', 'sms_offers_enabled');
            $table->renameColumn('sms_offer_message', 'sms_message');
            $table->dropColumn(['sms_first_entry_enabled','sms_first_entry_message']);
        });
    }
};
