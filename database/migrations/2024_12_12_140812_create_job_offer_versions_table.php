<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOfferVersionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('job_offer_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_offer_id')->constrained('job_offers')->onDelete('cascade');
            $table->string('job_title')->nullable();
            $table->text('description')->nullable();
            $table->string('work_mode')->nullable();
            $table->string('location')->nullable();
            $table->string('work_type')->nullable();
            $table->foreignId('process_log_id')->constrained('process_logs')->onDelete('cascade');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_offer_versions');
    }
}