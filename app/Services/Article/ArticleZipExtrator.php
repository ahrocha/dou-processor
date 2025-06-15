<?php

namespace App\Services\Article;

use App\Models\Upload;

class ArticleZipExtrator
{
    public function extrair(Upload $upload): array
    {
        $caminhoCompleto = storage_path('app/' . $upload->caminho_arquivo);

        if (!file_exists($caminhoCompleto)) {
            logger()->error("Arquivo ZIP não encontrado: " . $caminhoCompleto);
            return [];
        }

        logger()->info("Iniciando processamento do arquivo: " . $caminhoCompleto);

        logger()->info("Extraindo ZIP: " . $caminhoCompleto);

        $zip = new \ZipArchive();
        $res = $zip->open($caminhoCompleto);

        if ($res !== true) {
            logger()->error("Erro ao abrir ZIP: código $res");
            return [];
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
        return $arquivos;
    }
}
