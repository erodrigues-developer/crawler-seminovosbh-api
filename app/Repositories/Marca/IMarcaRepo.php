<?php namespace App\Repositories\Marca;

interface IMarcaRepo
{
    public function obterMarca($request);
    public function obterResultadosBusca($crawler);
    public function montarModelMarca($codigo, $marca);
}
