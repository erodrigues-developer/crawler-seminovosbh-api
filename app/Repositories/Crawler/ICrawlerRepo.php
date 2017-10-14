<?php namespace App\Repositories\Crawler;

interface ICrawlerRepo
{
    public function obterDados($dados);
    public function obterQuantidadePaginas($crawler);
    public function obterFiltrosBusca($crawler);
    public function obterDadosFiltroSelect($crawler, $nomeFiltro);
    public function obterResultadosBusca($crawler);
    public function montarResultados($detalhesVeiculo, $anuncio);
}
