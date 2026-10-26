<?php

namespace App\Services;

use App\Repositories\ConfigRepository;
use Illuminate\Support\Facades\Storage;

class ConfigService
{
    protected $configRepository;
    protected $idConfig = 1;

    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    public function getConfig()
    {
        $config = $this->configRepository->getConfigById($this->idConfig);
        return $config;
    }

    public function getMetaImg()
    {
        $config = $this->configRepository->getConfigById($this->idConfig);

        // A imagem original já está publicada no disco padrão (R2 em produção),
        // portanto não é necessário gerar miniatura local para a metatag.
        if (empty($config?->metaimg) || ! Storage::disk()->exists($config->metaimg)) {
            return url(asset('backend/assets/images/image.jpg'));
        }

        return Storage::url($config->metaimg);
    }
}
