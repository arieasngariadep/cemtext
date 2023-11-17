<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnAndForeignKeyUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropForeign('statusId');
            $table->dropColumn(['verification_code', 'is_verified', 'status_id', 'expired_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropForeign('statusId');
            $table->dropColumn(['verification_code', 'is_verified', 'status_id', 'expired_date']);
        });
    }
}
?>
