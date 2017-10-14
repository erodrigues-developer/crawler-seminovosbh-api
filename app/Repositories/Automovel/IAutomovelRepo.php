<?php namespace App\Repositories\Automovel;

interface IAutomovelRepo
{
    public function obterAutomovel($request, $id);
    public function obterResultadosBusca($crawler);
    public function obterImagensVeiculo($crawler);
    public function montarModelAutomovel($detalhes, $acessorios, $observacoes, $contato, $nomeAnuncio, $valorVeiculo, $imagensVeiculo);
}