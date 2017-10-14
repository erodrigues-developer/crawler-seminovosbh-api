<?php

namespace App\Http\Controllers;

use App\Repositories\Crawler\ICrawlerRepo;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BuscaController extends Controller
{
    private $crawler;

    public function __construct(ICrawlerRepo $crawler)
    {
        $this->crawler = $crawler;
    }

    public function onGet(Request $request, $id = null)
    {
        $result = $this->crawler->obterDados($request);

        if (!$result) {
            throw new NotFoundHttpException();
        }

        return $result;
    }
}
