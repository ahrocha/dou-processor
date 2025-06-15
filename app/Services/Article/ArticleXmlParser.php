<?php

namespace App\Services\Article;

class ArticleXmlParser
{
    public function parse($arquivoXml)
    {

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
            return null;
        }

        $xml = simplexml_import_dom($dom);

        return $xml;

    }
}
