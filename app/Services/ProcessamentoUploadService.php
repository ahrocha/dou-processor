<?php

namespace App\Services;

use App\Models\Upload;
use App\Models\Artigo;
use App\Services\Article\ArticleZipExtrator;
use App\Services\Article\ArticleXmlParser;
use App\Services\Article\ArticleCreator;

class ProcessamentoUploadService
{
    public function processar(Upload $upload): void
    {
        $extrator = new ArticleZipExtrator();
        $arquivos = $extrator->extrair($upload);
        if (empty($arquivos)) {
            logger()->error("Nenhum arquivo XML encontrado para o upload #{$upload->id}");
            return;
        }

        foreach ($arquivos as $arquivoXml) {
            try {

                $xml = (new ArticleXmlParser())->parse($arquivoXml);
                if ($xml === null) {
                    logger()->error("Erro ao processar XML: $arquivoXml");
                    continue;
                }

                if (!isset($xml->article)) {
                    logger()->warning("Arquivo sem <article>: $arquivoXml");
                    continue;
                }
                $article = $xml->article;

                Artigo::create([
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

                logger()->info("Parse bem-sucedido: ID={$article['id']}, Título={$article->body->Titulo}, SubTitulo={$article->body->SubTitulo}, ArquivoXml={$arquivoXml}");

            } catch (\Exception $e) {
                logger()->error("Erro ao processar $arquivoXml: " . $e->getMessage());
            }
        }

        logger()->info("Processamento concluído para upload #{$upload->id}");
    }
}
