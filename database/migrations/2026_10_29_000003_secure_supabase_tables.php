<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
 private const TABLES=['migrations','users','password_reset_tokens','sessions','cache','cache_locks','jobs','job_batches','failed_jobs','exams','exam_codes','students'];
 public function up(): void {
  if (DB::getDriverName() !== 'pgsql') return;
  // Backend uses a direct Postgres connection. No client role needs these tables.
  foreach (self::TABLES as $table) {
   DB::statement('ALTER TABLE public."'.$table.'" ENABLE ROW LEVEL SECURITY');
   DB::statement('REVOKE ALL ON TABLE public."'.$table.'" FROM PUBLIC');
   foreach (['anon','authenticated'] as $role) {
    if (DB::selectOne('SELECT 1 AS present FROM pg_roles WHERE rolname = ?', [$role])) {
     DB::statement('REVOKE ALL ON TABLE public."'.$table.'" FROM "'.$role.'"');
    }
   }
  }
  foreach (['migrations_id_seq','users_id_seq','jobs_id_seq','failed_jobs_id_seq','exams_id_seq','exam_codes_id_seq','students_id_seq'] as $sequence) {
   if (! DB::selectOne('SELECT to_regclass(?) AS name', ['public.'.$sequence])->name) continue;
   DB::statement('REVOKE ALL ON SEQUENCE public."'.$sequence.'" FROM PUBLIC');
   foreach (['anon','authenticated'] as $role) {
    if (DB::selectOne('SELECT 1 AS present FROM pg_roles WHERE rolname = ?', [$role])) DB::statement('REVOKE ALL ON SEQUENCE public."'.$sequence.'" FROM "'.$role.'"');
   }
  }
 }
 // Intentionally do not disable RLS or restore API grants on rollback.
 public function down(): void {}
};
