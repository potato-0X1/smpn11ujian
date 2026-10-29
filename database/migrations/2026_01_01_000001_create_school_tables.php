<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('exams', function(Blueprint $t){ $t->id(); $t->string('title',150); $t->string('subject',100); $t->string('form_url',2048); $t->date('exam_date'); $t->string('status')->default('draft'); $t->timestamps(); });
  Schema::create('exam_codes', function(Blueprint $t){ $t->id(); $t->foreignId('exam_id')->constrained()->cascadeOnDelete(); $t->string('class_name',2); $t->string('code',40)->unique(); $t->unique(['exam_id','class_name']); });
  Schema::create('students', function(Blueprint $t){ $t->id(); $t->string('nis',30)->unique(); $t->string('name',120); $t->string('class_name',2); $t->string('password'); $t->timestamps(); });
 }
 public function down(): void { Schema::dropIfExists('students'); Schema::dropIfExists('exam_codes'); Schema::dropIfExists('exams'); }
};
