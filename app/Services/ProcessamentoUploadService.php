<?php

namespace App\Services;

use App\Models\Upload;
use App\Models\Artigo;

class ProcessamentoUploadService
{
    public function processar(Upload $upload): void
    {
        $caminhoCompleto = storage_path('app/' . $upload->caminho_arquivo);

        if (!file_exists($caminhoCompleto)) {
            logger()->error("Arquivo ZIP não encontrado: " . $caminhoCompleto);
            return;
        }

        logger()->info("Iniciando processamento do arquivo: " . $caminhoCompleto);

        logger()->info("Extraindo ZIP: " . $caminhoCompleto);

        $zip = new \ZipArchive;
        $res = $zip->open($caminhoCompleto);

        if ($res !== true) {
            logger()->error("Erro ao abrir ZIP: código $res");
            return;
        }

        // Diretório temporário
        $pastaExtracao = storage_path('app/temp/extracao_' . $upload->id);

        if (!file_exists($pastaExtracao)) {
            mkdir($pastaExtracao, 0775, true);
        }

        // descompacta o ZIP
        $zip->extractTo($pastaExtracao);
        $zip->close();

        logger()->info("Arquivos extraídos em: $pastaExtracao");

        // Itera pelos arquivos XML extraídos
        $arquivos = glob($pastaExtracao . '/*.xml');

        foreach ($arquivos as $arquivoXml) {
            try {
                libxml_use_internal_errors(true);

                $dom = new \DOMDocument();

                $conteudo = file_get_contents($arquivoXml);
                $ok = $dom->loadXML($conteudo, LIBXML_NOCDATA);

                if (!$ok) {
                    $erros = libxml_get_errors();
                    logger()->error("Erro ao processar $arquivoXml:");
                    foreach ($erros as $erro) {
                        logger()->error(trim($erro->message));
                    }
                    libxml_clear_errors();
                    continue;
                }

                $xml = simplexml_import_dom($dom);

                if (!isset($xml->article)) {
                    logger()->warning("Arquivo sem <article>: $arquivoXml");
                    continue;
                }

                $article = $xml->article;
                $body = $article->body ?? null;

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

                logger()->info("Parse bem-sucedido: ID={$article['id']}, Título={$body->Titulo}, SubTitulo={$body->SubTitulo}, ArquivoXml={$arquivoXml}");

            } catch (\Exception $e) {
                logger()->error("Erro ao processar $arquivoXml: " . $e->getMessage());
            }
        }

        logger()->info("Processamento concluído para upload #{$upload->id}");
    }
}
