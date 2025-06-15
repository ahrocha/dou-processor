<?php

function gerarCombinacoes(array $garrafas): array {
    $n = count($garrafas);
    $todas = [];

    for ($r = 1; $r <= $n; $r++) {
        gerarCombinacoesRecursivas($garrafas, [], 0, $r, $todas);
    }

    return $todas;
}

function gerarCombinacoesRecursivas(array $garrafas, array $atual, int $inicio, int $r, array &$todas) {
    if (count($atual) === $r) {
        $todas[] = $atual;
        return;
    }

    for ($i = $inicio; $i < count($garrafas); $i++) {
        gerarCombinacoesRecursivas($garrafas, [...$atual, $garrafas[$i]], $i + 1, $r, $todas);
    }
}

function escolherGarrafasParaGalao(float $volumeGalao, array $garrafas): array {
    $combinacoes = gerarCombinacoes($garrafas);
    $melhorComb = [];
    $melhorSobra = $volumeGalao;
    $menorNumeroGarrafas = PHP_INT_MAX;
    $epsilon = 0.0001;

    foreach ($combinacoes as $comb) {
        $soma = array_sum($comb);

        if ($soma + $epsilon < $volumeGalao) {
            continue;
        }

        $sobra = $soma - $volumeGalao;

        if (
            ($sobra < $melhorSobra - $epsilon) ||
            (abs($sobra - $melhorSobra) < $epsilon && count($comb) < $menorNumeroGarrafas)
        ) {
            $melhorComb = $comb;
            $melhorSobra = $sobra;
            $menorNumeroGarrafas = count($comb);
        }

        if (abs($melhorSobra) < $epsilon) {
            break;
        }
    }

    sort($melhorComb);

    return [
        'garrafas_usadas' => $melhorComb,
        'sobra' => round($melhorSobra, 2)
    ];
}

$volumeGalao = (float) readline("Insira o volume do galão:\n");
$qtdGarrafas = (int) readline("Quantidade de garrafas:\n");
$garrafas = [];

for ($i = 0; $i < $qtdGarrafas; $i++) {
    $v = (float) readline("Garrafa " . ($i + 1) . ":\n");
    $garrafas[] = $v;
}

$resultado = escolherGarrafasParaGalao($volumeGalao, $garrafas);
$formatadas = array_map(fn($v) => number_format($v, 1), $resultado['garrafas_usadas']);
echo "Resposta: [" . implode("L, ", $formatadas) . "L]";
echo ", sobra: " . number_format($resultado['sobra'], 2) . "L\n";
