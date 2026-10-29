-- SMPN 11 Singkawang — inisialisasi awal untuk pengujian produksi Laravel + Filament
-- PERINGATAN: jalankan hanya pada project KHUSUS YANG BARU DAN KOSONG.
-- Skrip ini diuji di PostgreSQL lokal, BELUM diuji di project Supabase Anda.
-- Skrip ini tidak menjamin kesiapan produksi; lakukan review dan backup terlebih dahulu.
-- Tidak membuat akun guru dan tidak memasukkan data contoh.
-- Jika tabel bernama sama sudah ada, skrip berhenti dan tidak mengubah data.

BEGIN;

-- Tolak database yang sudah memiliki tabel project ini agar tidak menimpa data.
DO $$
DECLARE
    nama text;
BEGIN
    FOREACH nama IN ARRAY ARRAY[
        'migrations', 'users', 'password_reset_tokens', 'sessions',
        'cache', 'cache_locks', 'jobs', 'job_batches', 'failed_jobs',
        'exams', 'exam_codes', 'students', 'audit_logs'
    ] LOOP
        IF to_regclass(format('public.%I', nama)) IS NOT NULL THEN
            RAISE EXCEPTION 'Tabel public.% sudah ada. Hentikan dan periksa project ini sebelum melanjutkan.', nama;
        END IF;
    END LOOP;
END $$;

-- Catatan migrasi Laravel: mencocokkan nama migration pada source project.
CREATE TABLE public.migrations (
    id serial PRIMARY KEY,
    migration varchar(255) NOT NULL,
    batch integer NOT NULL
);

CREATE TABLE public.users (
    id bigserial PRIMARY KEY,
    name varchar(255) NOT NULL,
    email varchar(255) NOT NULL UNIQUE,
    email_verified_at timestamp(0) without time zone,
    password varchar(255) NOT NULL,
    remember_token varchar(100),
    is_teacher boolean NOT NULL DEFAULT false,
    app_authentication_secret text,
    app_authentication_recovery_codes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
CREATE TABLE public.password_reset_tokens (
    email varchar(255) PRIMARY KEY,
    token varchar(255) NOT NULL,
    created_at timestamp(0) without time zone
);
CREATE TABLE public.sessions (
    id varchar(255) PRIMARY KEY,
    user_id bigint,
    ip_address varchar(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);
CREATE INDEX sessions_user_id_index ON public.sessions (user_id);
CREATE INDEX sessions_last_activity_index ON public.sessions (last_activity);

CREATE TABLE public.cache (
    key varchar(255) PRIMARY KEY,
    value text NOT NULL,
    expiration bigint NOT NULL
);
CREATE INDEX cache_expiration_index ON public.cache (expiration);
CREATE TABLE public.cache_locks (
    key varchar(255) PRIMARY KEY,
    owner varchar(255) NOT NULL,
    expiration bigint NOT NULL
);
CREATE INDEX cache_locks_expiration_index ON public.cache_locks (expiration);

CREATE TABLE public.jobs (
    id bigserial PRIMARY KEY,
    queue varchar(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);
CREATE INDEX jobs_queue_index ON public.jobs (queue);
CREATE TABLE public.job_batches (
    id varchar(255) PRIMARY KEY,
    name varchar(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);
CREATE TABLE public.failed_jobs (
    id bigserial PRIMARY KEY,
    uuid varchar(255) NOT NULL UNIQUE,
    connection varchar(255) NOT NULL,
    queue varchar(255) NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX failed_jobs_connection_queue_failed_at_index ON public.failed_jobs (connection, queue, failed_at);

CREATE TABLE public.exams (
    id bigserial PRIMARY KEY,
    title varchar(150) NOT NULL,
    subject varchar(100) NOT NULL,
    form_url varchar(2048) NOT NULL,
    exam_date date NOT NULL,
    status varchar(255) NOT NULL DEFAULT 'draft',
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
CREATE TABLE public.exam_codes (
    id bigserial PRIMARY KEY,
    exam_id bigint NOT NULL REFERENCES public.exams(id) ON DELETE CASCADE,
    class_name varchar(2) NOT NULL,
    code varchar(40) NOT NULL UNIQUE,
    UNIQUE (exam_id, class_name)
);
CREATE TABLE public.students (
    id bigserial PRIMARY KEY,
    nis varchar(30) NOT NULL UNIQUE,
    name varchar(120) NOT NULL,
    class_name varchar(2) NOT NULL,
    password varchar(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);

CREATE TABLE public.audit_logs (
    id bigserial PRIMARY KEY,
    actor_id bigint,
    action varchar(40) NOT NULL,
    subject_type varchar(30) NOT NULL,
    subject_id bigint,
    created_at timestamp(0) without time zone NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX audit_logs_actor_id_index ON public.audit_logs (actor_id);
CREATE INDEX audit_logs_created_at_index ON public.audit_logs (created_at);

-- Supabase Data API tidak boleh mengakses tabel-tabel ini dari anon/authenticated.
-- Laravel mengaksesnya lewat koneksi PostgreSQL server-side yang sah, bukan anon key.
DO $$
DECLARE
    nama text;
BEGIN
    FOREACH nama IN ARRAY ARRAY[
        'migrations', 'users', 'password_reset_tokens', 'sessions',
        'cache', 'cache_locks', 'jobs', 'job_batches', 'failed_jobs',
        'exams', 'exam_codes', 'students', 'audit_logs'
    ] LOOP
        EXECUTE format('ALTER TABLE public.%I ENABLE ROW LEVEL SECURITY', nama);
        EXECUTE format('REVOKE ALL ON TABLE public.%I FROM PUBLIC, anon, authenticated', nama);
    END LOOP;
END $$;
REVOKE ALL ON SEQUENCE public.migrations_id_seq, public.users_id_seq,
    public.jobs_id_seq, public.failed_jobs_id_seq, public.exams_id_seq,
    public.exam_codes_id_seq, public.students_id_seq, public.audit_logs_id_seq FROM PUBLIC, anon, authenticated;

INSERT INTO public.migrations (migration, batch) VALUES
    ('0001_01_01_000000_create_users_table', 1),
    ('0001_01_01_000001_create_cache_table', 1),
    ('0001_01_01_000002_create_jobs_table', 1),
    ('2026_01_01_000001_create_school_tables', 1),
    ('2026_10_29_000002_secure_teacher_accounts', 1),
    ('2026_10_29_000003_secure_supabase_tables', 1),
    ('2026_10_29_000004_create_audit_logs', 1);

COMMIT;

-- Selesai. Akun guru dan konfigurasi koneksi Laravel adalah langkah terpisah.
-- Hubungkan server Laravel dengan akun database server-side berhak akses sesuai rancangan.
-- RLS dan REVOKE bukan pengganti pengujian hak akses, keamanan API, dan review produksi.
