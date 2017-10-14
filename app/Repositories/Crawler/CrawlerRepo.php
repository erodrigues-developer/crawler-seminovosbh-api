<?php namespace App\Repositories\Crawler;

use App\Models\Crawler\ResultadoBusca;
use Weidner\Goutte\GoutteFacade as Goutte;

class CrawlerRepo implements ICrawlerRepo
{
    const URL_SEMINOVOS_BUSCA_TOTAL_CARRO = 'https://www.seminovosbh.com.br/resultadobusca/index/veiculo/carro/estado-conservacao/seminovo/usuario/todos';
    const URL_SEMINOVOS_BUSCA_MARCA = 'https://www.seminovosbh.com.br/resultadobusca/index/veiculo/carro/estado-conservacao/seminovo%susuario/todos/pagina/%s';

    public function CrawlerRepo()
    {

    }
    /**
     * Obtém os resultados da pesquisa de acordo com os filtros passados
     */
    public function obterDados($request)
    {
        $url = '';
        if ($request->has('marca')) {
            $url .= 'marca/' . $request->get('marca') . '/';
        }

        if ($request->has('cidade')) {
            $url .= 'cidade/' . $request->get('cidade') . '/';
        }

        if ($request->has('valorDe')) {
            $url .= 'valor1/' . $request->get('valor1') . '/';
        }

        if ($request->has('valorAte')) {
            $url .= 'valor2/' . $request->get('valor2') . '/';
        }

        if ($request->has('anoDe')) {
            $url .= 'ano1/' . $request->get('ano1') . '/';
        }

        if ($request->has('anoAte')) {
            $url .= 'ano2/' . $request->get('ano2') . '/';
        }

        $pagina = $request->has('pagina') ? $request->get('pagina') : 1;

        if ($url != '') {
            $url = '/' . $url;
        } else {
            $url = '/';
        }

        $caminho = sprintf(CrawlerRepo::URL_SEMINOVOS_BUSCA_MARCA, $url, $pagina);


        $crawler = Goutte::request('GET', $caminho);
        $quantidadePaginasCarros = $this->obterQuantidadePaginas($crawler);
        $resultados = $this->obterResultadosBusca($crawler);

        $veiculos = [];
        if ($resultados) {
            foreach ($resultados as $key => $value) {
                $veiculos[] = $this->montarResultados($value, $key);
            }
        }
        return $veiculos;
    }
    /**
     * Obtém a quantidade de páginas para montar paginação
     */
    public function obterQuantidadePaginas($crawler)
    {
        $resultado = $crawler->filter('.total')->each(function ($node) {
            return intval($node->text());
        });
        return !empty($resultado) ? $resultado[0] : 0;
    }
    /**
     * Obtém os dados dos filtros para pesquisa
     */
    public function obterFiltrosBusca($crawler)
    {
        $filtroMarcas = $this->obterDadosFiltroSelect($crawler, '#marca');
        $filtroCidades = $this->obterDadosFiltroSelect($crawler, '#idCidade');
        $filtroValorTo = $this->obterDadosFiltroSelect($crawler, '#valor1');
        $filtroValorFrom = $this->obterDadosFiltroSelect($crawler, '#valor2');
        $filtroAnoTo = $this->obterDadosFiltroSelect($crawler, '#ano1');
        $filtroAnoFrom = $this->obterDadosFiltroSelect($crawler, '#ano2');
        return [
            'marcas' => $filtroMarcas,
            'cidades' => $filtroCidades,
            'valoresTo' => $filtroValorTo,
            'valoresFrom' => $filtroValorFrom,
            'anoTo' => $filtroAnoTo,
            'anoFrom' => $filtroAnoFrom,
        ];
    }

    public function obterDadosFiltroSelect($crawler, $nomeFiltro)
    {
        return $crawler->filter($nomeFiltro)->filterXPath('//option[contains(@value, "")]')->each(function ($node) {
            $elemento[$node->extract(['value'])[0]] = $node->text();
            return $elemento;
        });
    }
    /**
     * Método responsável por obter os resultados da busca
     */
    public function obterResultadosBusca($crawler)
    {
        $nomes = $crawler->filter('.bg-busca .titulo-busca')->each(function ($node) {
            $desc[] = trim($node->text());
            return $desc;
        });
        $links = $crawler->filter('.bg-busca > dt')->filterXPath('//a[contains(@href, "")]')->each(function ($node) {
            return $elemento[] = $node->extract(['href'])[0];
        });

        $linksDetalhes = [];
        foreach ($links as $key => $value) {
            if (substr($value, 0, 15) != '/veiculo/codigo') {
                $linksDetalhes[] = $value;
            }
        }

        $arrayFinal = [];
        foreach ($nomes as $key => $value) {
            $arrayFinal[$value[0]] = $linksDetalhes[$key];
        }
        return $arrayFinal;
    }

    public function montarResultados($detalhesVeiculo, $anuncio)
    {
        $dados = explode('/', $detalhesVeiculo);
        $resultado = new ResultadoBusca();
        $resultado->ano = $dados[4];
        $resultado->id = $dados[5];
        $resultado->marca = $dados[2];
        $resultado->nome = $dados[3];
        $resultado->anuncio = $anuncio;
        return $resultado;
    }
}
