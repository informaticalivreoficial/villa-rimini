<?php

namespace App\Services;

use App\Repositories\ConfigRepository;
use App\Support\Media;
use Illuminate\Support\Facades\Storage;

class ConfigService
{
    protected $configRepository;
    protected $idConfig = 1;
    protected $config;

    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    public function getConfig()
    {
        if ($this->config === null) {
            $this->config = $this->configRepository->getConfigById($this->idConfig);
        }

        return $this->config;
    }

    public function getMetaImg()
    {
        $config = $this->getConfig();

        // A imagem original já está publicada no disco padrão (R2 em produção),
        // portanto não é necessário gerar miniatura local para a metatag.
        if (empty($config?->metaimg) || ! Media::exists($config->metaimg)) {
            return url(asset('backend/assets/images/image.jpg'));
        }

        return Storage::url($config->metaimg);
    }
}
