<?php

namespace App\Services;

use App\Repositories\CidadeRepository;

class CidadeService
{
    protected $cidadeRepository;

    public function __construct(CidadeRepository $cidadeRepository)
    {
        $this->cidadeRepository = $cidadeRepository;
    }

    public function getCidades()
    {
        $cidades = $this->cidadeRepository->getCidades();
        return $cidades;
    }
}