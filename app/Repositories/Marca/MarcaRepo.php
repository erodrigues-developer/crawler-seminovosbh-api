<?php namespace App\Repositories\Marca;

use App\Models\Marca\Marca;
use Weidner\Goutte\GoutteFacade as Goutte;

class MarcaRepo implements IMarcaRepo
{
    const URL_SEMINOVOS_BUSCA_MARCA = 'https://www.seminovosbh.com.br';

    public function __contruct()
    {

    }
    /**
     * Obtém os resultados da pesquisa de acordo com os filtros passados
     */
    public function obterMarca($request)
    {
        $caminho = sprintf(MarcaRepo::URL_SEMINOVOS_BUSCA_MARCA);

        $crawler = Goutte::request('GET', $caminho);
        $resultados = $this->obterResultadosBusca($crawler);

        return ($resultados);
    }

    /**
     * Método responsável por obter os resultados da busca
     */
    public function obterResultadosBusca($crawler)
    {
        $codigo = $crawler->filter('#marca')->filterXPath('//option[contains(@value, "")]')->each(function ($node) {
            return $node->extract(['value'])[0];
        });

        $nome = $crawler->filter('#marca > option')->each(function ($node) {
            return trim($node->text());
        });

        $marca = array();

        foreach ($codigo as $key => $value) {
            $marca[] = $this->montarModelMarca($value, $nome[$key]);
        }

        return $marca;
    }

    /**
     * Método responsável por montar o model com os resultados da busca
     */
    public function montarModelMarca($codigo, $marca)
    {
        $resultado = new Marca();
        $resultado->codigo = $codigo;
        $resultado->marca = $marca;

        return $resultado;
    }
}
