<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artigo extends Model
{
    /** @use HasFactory<\Database\Factories\ArtigoFactory> */
    use HasFactory;

    protected $fillable = [
        'upload_id',
        'article_id',
        'name',
        'id_oficio',
        'pub_name',
        'art_type',
        'pub_date',
        'art_class',
        'art_category',
        'art_size',
        'art_notes',
        'number_page',
        'pdf_page',
        'edition_number',
        'identifica',
        'data',
        'ementa',
        'titulo',
        'sub_titulo',
        'texto',
    ];

}
