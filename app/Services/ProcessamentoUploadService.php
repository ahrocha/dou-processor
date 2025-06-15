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

        logger()->info("Processando upload #{$upload->id} com " . count($arquivos) . " arquivos XML.");

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

                $newArticle = new ArticleCreator();
                $article = $newArticle->create($article, $upload);

                logger()->info("Parse bem-sucedido: ID={$article['id']}, Título={$article->titulo}, SubTitulo={$article->sub_titulo}, ArquivoXml={$arquivoXml}");

            } catch (\Exception $e) {
                logger()->error("Erro ao processar upload $upload->id - $arquivoXml: " . $e->getMessage());
            }
        }

        logger()->info("Processamento concluído para upload #{$upload->id}");
    }
}
