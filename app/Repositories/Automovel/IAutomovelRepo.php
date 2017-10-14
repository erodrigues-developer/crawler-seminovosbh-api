<?php namespace App\Repositories\Automovel;

interface IAutomovelRepo
{
    public function obterAutomovel($request, $id);
    public function obterResultadosBusca($crawler, $id);
    public function obterImagensVeiculo($crawler);
    public function montarModelAutomovel($id, $detalhes, $acessorios, $observacoes, $contato, $nomeAnuncio, $valorVeiculo, $imagensVeiculo);
}
