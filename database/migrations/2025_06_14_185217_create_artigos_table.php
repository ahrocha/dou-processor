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
        Schema::create('artigos', function (Blueprint $table) {
    $table->id();

    $table->foreignId('upload_id')->constrained('uploads')->onDelete('cascade');

    $table->string('article_id')->nullable();
    $table->string('name')->nullable();
    $table->string('id_oficio')->nullable();
    $table->string('pub_name')->nullable();
    $table->string('art_type')->nullable();
    $table->date('pub_date')->nullable();
    $table->string('art_class')->nullable();
    $table->string('art_category')->nullable();
    $table->string('art_size')->nullable();
    $table->text('art_notes')->nullable();
    $table->string('number_page')->nullable();
    $table->string('pdf_page')->nullable();
    $table->string('edition_number')->nullable();

    $table->text('identifica')->nullable();
    $table->text('data')->nullable();
    $table->text('ementa')->nullable();
    $table->text('titulo')->nullable();
    $table->text('sub_titulo')->nullable();
    $table->longText('texto')->nullable();

    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artigos');
    }
};
