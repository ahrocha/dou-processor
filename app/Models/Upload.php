<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    /** @use HasFactory<\Database\Factories\UploadFactory> */
    use HasFactory;

    protected $fillable = [
        'nome_original',
        'caminho_arquivo',
    ];

    public function artigos()
    {
        return $this->hasMany(\App\Models\Artigo::class);
    }
}
