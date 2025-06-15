<?php

namespace App\Services\Article;

use App\Models\Artigo;
use App\Models\Upload;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class ArticleCreator
{
    public function create(SimpleXMLElement $article, Upload $upload): Artigo
    {
        Log::info("Iniciando criação do Artigo para upload #{$upload->id}. Article ID: {$article['article_id']}");

        try {
            $articleData['upload_id'] = $upload->id;

            $artigo = Artigo::create([
                'upload_id' => $upload->id,
                'article_id' => (string) $article['id'],
                'name' => (string) $article['name'],
                'id_oficio' => (string) $article['idOficio'],
                'pub_name' => (string) $article['pubName'],
                'art_type' => (string) $article['artType'],
                'pub_date' => !empty($article['pubDate']) ? date('Y-m-d', strtotime((string)$article['pubDate'])) : null,
                'art_class' => (string) $article['artClass'],
                'art_category' => (string) $article['artCategory'],
                'art_size' => (string) $article['artSize'],
                'art_notes' => (string) $article['artNotes'],
                'number_page' => (string) $article['numberPage'],
                'pdf_page' => (string) $article['pdfPage'],
                'edition_number' => (string) $article['editionNumber'],
                'identifica' => (string) $article->body->Identifica,
                'data' => (string) $article->body->Data,
                'ementa' => (string) $article->body->Ementa,
                'titulo' => (string) $article->body->Titulo,
                'sub_titulo' => (string) $article->body->SubTitulo,
                'texto' => (string) $article->body->Texto,
            ]);

            Log::info("Artigo criado com sucesso. ID do Artigo (DB): {$artigo->id}, Article ID (XML): {$articleData['article_id']}");

            return $artigo;

        } catch (\Throwable $e) {
            Log::error("Erro ao criar Artigo para upload #{$upload->id}. Article ID: {$articleData['article_id']}. Erro: " . $e->getMessage(), ['exception' => $e]);
            throw new \Exception("Falha ao persistir o artigo no banco de dados.", 0, $e);
        }
    }
}
