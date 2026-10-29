<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('audit_logs', function (Blueprint $table): void {
   $table->id();
   $table->unsignedBigInteger('actor_id')->nullable()->index();
   $table->string('action',40);
   $table->string('subject_type',30);
   $table->unsignedBigInteger('subject_id')->nullable();
   $table->timestamp('created_at')->useCurrent()->index();
  });
  if (DB::getDriverName() !== 'pgsql') return;
  DB::statement('ALTER TABLE public.audit_logs ENABLE ROW LEVEL SECURITY');
  DB::statement('REVOKE ALL ON TABLE public.audit_logs FROM PUBLIC');
  DB::statement('REVOKE ALL ON SEQUENCE public.audit_logs_id_seq FROM PUBLIC');
  foreach (['anon','authenticated'] as $role) {
   if (DB::selectOne('SELECT 1 AS present FROM pg_roles WHERE rolname = ?', [$role])) {
    DB::statement('REVOKE ALL ON TABLE public.audit_logs FROM "'.$role.'"');
    DB::statement('REVOKE ALL ON SEQUENCE public.audit_logs_id_seq FROM "'.$role.'"');
   }
  }
 }
 public function down(): void {Schema::dropIfExists('audit_logs');}
};
