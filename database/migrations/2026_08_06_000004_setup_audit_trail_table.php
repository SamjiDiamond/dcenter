<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetupAuditTrailTable extends Migration
{
    /**
     * The audit_trail table was created out-of-band (no migration exists in this
     * repo). If it is missing, create it with the full schema; otherwise add any
     * missing columns needed by the audit log feature.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('audit_trail')) {
            Schema::create('audit_trail', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('admin_id')->nullable()->index();
                $table->string('role')->nullable();
                $table->string('action')->nullable();
                $table->string('type')->nullable();
                $table->text('description')->nullable();
                $table->string('subject_type')->nullable();
                $table->unsignedBigInteger('subject_id')->nullable();
                $table->string('severity', 20)->default('info');
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();
            });

            return;
        }

        Schema::table('audit_trail', function (Blueprint $table) {
            if (! Schema::hasColumn('audit_trail', 'role')) {
                $table->string('role')->nullable();
            }
            if (! Schema::hasColumn('audit_trail', 'description')) {
                $table->text('description')->nullable();
            }
            if (! Schema::hasColumn('audit_trail', 'subject_type')) {
                $table->string('subject_type')->nullable();
            }
            if (! Schema::hasColumn('audit_trail', 'subject_id')) {
                $table->unsignedBigInteger('subject_id')->nullable();
            }
            if (! Schema::hasColumn('audit_trail', 'severity')) {
                $table->string('severity', 20)->default('info');
            }
            if (! Schema::hasColumn('audit_trail', 'ip_address')) {
                $table->string('ip_address', 45)->nullable();
            }
            if (! Schema::hasColumn('audit_trail', 'user_agent')) {
                $table->text('user_agent')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasTable('audit_trail')) {
            return;
        }

        Schema::table('audit_trail', function (Blueprint $table) {
            foreach (['role', 'description', 'subject_type', 'subject_id', 'severity', 'ip_address', 'user_agent'] as $column) {
                if (Schema::hasColumn('audit_trail', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
