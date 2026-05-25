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
        Schema::table('users', function (Blueprint $table) {
            // بنضيف عمود الصورة وخليه nullable عشان لو يوزر مسجلش بصورة ما يضربش
            // وعملناه بعد عمود الـ role عشان ترتيب الجدول يفضل شيك ونظيف
            $table->string('image')->nullable()->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // اللوجيك العكسي لو حبيت تعمل rollback للميجريشن
            $table->dropColumn('image');
        });
    }
};