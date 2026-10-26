<?php

namespace Tests\Feature;

use App\Services\ConfigService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\CreatesTestData;
use Tests\TestCase;

class FilesystemTest extends TestCase
{
    use CreatesTestData;
    use RefreshDatabase;

    public function test_testes_nao_usam_o_disco_remoto(): void
    {
        $this->assertSame('local', config('filesystems.default'));
    }

    public function test_disco_r2_monta_url_publica_com_prefixo_do_projeto(): void
    {
        config([
            'filesystems.disks.r2.url' => 'https://cdn.exemplo.com/',
            'filesystems.disks.r2.root' => '/villarimini/',
        ]);

        Storage::forgetDisk('r2');

        $this->assertSame(
            'https://cdn.exemplo.com/villarimini/slides/foto.jpg',
            Storage::disk('r2')->url('slides/foto.jpg')
        );
    }

    public function test_upload_grava_no_disco_padrao_e_gera_url_publica(): void
    {
        Storage::fake('local');

        $path = UploadedFile::fake()
            ->image('foto.jpg')
            ->storeAs('slides', 'foto-teste.jpg');

        $this->assertSame('slides/foto-teste.jpg', $path);
        Storage::disk('local')->assertExists($path);
        $this->assertStringContainsString('/storage/slides/foto-teste.jpg', Storage::url($path));
    }

    public function test_metaimg_usa_arquivo_do_disco_e_tem_fallback(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('configuracoes/meta.png', 'conteudo');

        $this->createConfig(['metaimg' => 'configuracoes/meta.png']);
        $service = app(ConfigService::class);

        $this->assertSame(Storage::url('configuracoes/meta.png'), $service->getMetaImg());

        Storage::disk('local')->delete('configuracoes/meta.png');

        $this->assertStringContainsString('backend/assets/images/image.jpg', $service->getMetaImg());
    }

    public function test_sitemap_e_servido_a_partir_do_disco_padrao(): void
    {
        Storage::fake('local');

        $config = $this->createConfig(['sitemap' => 'hotel-villa-di-rimini_sitemap.xml']);
        Storage::disk('local')->put($config->sitemap, '<?xml version="1.0"?><urlset></urlset>');

        $this->get(route('web.sitemap'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('<urlset>', false);
    }

    public function test_sitemap_inexistente_retorna_404(): void
    {
        Storage::fake('local');

        $this->createConfig(['sitemap' => 'nao-existe.xml']);

        $this->get(route('web.sitemap'))->assertNotFound();
    }
}
