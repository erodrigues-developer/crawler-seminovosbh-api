<?php

namespace App\Http\Controllers;

use App\Repositories\Marca\IMarcaRepo;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MarcaController extends Controller
{
    private $marca;

    public function __construct(IMarcaRepo $marca)
    {
        $this->marca = $marca;
    }

    public function onGet(Request $request, $id = null)
    {
        $result = $this->marca->obterMarca($request);

        if (!$result) {
            throw new NotFoundHttpException();
        }

        return $result;
    }
}
