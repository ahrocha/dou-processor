<?php

namespace App\Services;

use App\Models\Upload;

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

                logger()->info("Parse bem-sucedido: ID={$article['id']}, Título={$body->Titulo}, SubTitulo={$body->SubTitulo}, ArquivoXml={$arquivoXml}");

            } catch (\Exception $e) {
                logger()->error("Erro ao processar $arquivoXml: " . $e->getMessage());
            }
        }

        logger()->info("Processamento concluído para upload #{$upload->id}");
    }
}
