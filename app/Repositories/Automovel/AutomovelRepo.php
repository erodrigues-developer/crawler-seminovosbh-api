<?php namespace App\Repositories\Automovel;

use App\Models\Automovel\Automovel;
use Weidner\Goutte\GoutteFacade as Goutte;

class AutomovelRepo implements IAutomovelRepo
{
    const URL_SEMINOVOS_BUSCA_AUTOMOVEL = 'https://www.seminovosbh.com.br/comprar////%s';

    public function __contruct()
    {

    }
    /**
     * Obtém os resultados da pesquisa de acordo com os filtros passados
     */
    public function obterAutomovel($request, $id)
    {
        if (!$id) {
            return [];
        }

        $caminho = sprintf(AutomovelRepo::URL_SEMINOVOS_BUSCA_AUTOMOVEL, $id);

        $crawler = Goutte::request('GET', $caminho);
        $resultados = $this->obterResultadosBusca($crawler, $id);

        return ($resultados);
    }

    /**
     * Método responsável por obter os resultados da busca
     */
    public function obterResultadosBusca($crawler, $id)
    {
        $imagem = $crawler->filter('#conteudo')->filterXPath('//img[contains(@src, "")]')->each(function ($node) {
            return $node->extract(['src'])[0];
        });

        foreach ($imagem as $key => $value) {
            if (strpos($value, 'veiculoNaoExiste.png') !== false) {
                return [];
            }
        }

        $imagensVeiculo = $this->obterImagensVeiculo($crawler);

        $nomeAnuncio = $crawler->filter('#textoBoxVeiculo > h5')->each(function ($node) {
            return trim($node->text());
        });

        $valorVeiculo = $crawler->filter('#textoBoxVeiculo > p')->each(function ($node) {
            return trim($node->text());
        });

        $detalhes = $crawler->filter('#infDetalhes > span > ul > li')->each(function ($node) {
            return trim($node->text());
        });

        $acessorios = $crawler->filter('#infDetalhes2 > ul > li')->each(function ($node) {
            return trim($node->text());
        });

        $observacoes = $crawler->filter('#infDetalhes3 > ul > p')->each(function ($node) {
            return trim($node->text());
        });

        $contato = $crawler->filter('#infDetalhes4 .texto> ul > li')->each(function ($node) {
            return trim($node->text());
        });

        return [$this->montarModelAutomovel($id, $detalhes, $acessorios, $observacoes, $contato, $nomeAnuncio, $valorVeiculo, $imagensVeiculo)];
    }

    /**
     * Método responsável por obter as imagens do resultados da busca
     */
    public function obterImagensVeiculo($crawler)
    {
        $imagemPrincipal = $crawler->filter('#fotoVeiculo')->filterXPath('//img[contains(@src, "")]')->each(function ($node) {
            return $node->extract(['src'])[0];
        });

        $imagens = $crawler->filter('#conteudoVeiculo')->filterXPath('//img[contains(@src, "")]')->each(function ($node) {
            return $node->extract(['src'])[0];
        });

        $imagensVeiculo = [];
        foreach ($imagens as $key => $value) {
            if (strpos($value, 'photoNone.jpg') === false) {
                $imagensVeiculo[] = $value;
            }
        }
        return array_merge($imagemPrincipal, $imagensVeiculo);
    }

    /**
     * Método responsável por montar o model com os resultados da busca
     */
    public function montarModelAutomovel($id, $detalhes, $acessorios, $observacoes, $contato, $nomeAnuncio, $valorVeiculo, $imagensVeiculo)
    {
        $resultado = new Automovel();
        $resultado->codigo = $id;
        $resultado->nomeAnuncio = empty($nomeAnuncio) ? '' : $nomeAnuncio[0];
        $resultado->valorVeiculo = empty($valorVeiculo) ? '' : $valorVeiculo[0];
        $resultado->detalhes = $detalhes;
        $resultado->acessorios = $acessorios;
        $resultado->obsevacoes = empty($observacoes) ? '' : $observacoes[0];
        $resultado->imagens = $imagensVeiculo;

        foreach ($contato as $key => $value) {
            if (strpos($value, 'Visualizações:') !== false) {
                $resultado->visualizacoes = intval((explode('Visualizações: ', $value))[1]) ? intval((explode('Visualizações: ', $value))[1]) : null;
            }
            if (strpos($value, 'Cadastro em: ') !== false) {
                $resultado->dataCadastro = explode('Cadastro em: ', $value)[1] ? explode('Cadastro em: ', $value)[1] : null;
            }
        }

        $resultado->proprietario->nome = empty($contato) ? '' : $contato[0];
        $resultado->proprietario->cidade = empty($contato) ? '' : $contato[1];
        $resultado->proprietario->contato = empty($contato) ? '' : $contato[2];

        return $resultado;
    }
}
