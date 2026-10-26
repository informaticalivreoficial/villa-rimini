<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\ConfigService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Spatie\Sitemap\SitemapGenerator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    protected $configService;

    public function __construct(ConfigService $configService)
    {
        $this->configService = $configService;
    }

    public function gerarxml(Request $request)
    {
        $configupdate = $this->configService->getConfig();
        $sitemapPath = Str::slug($configupdate->nomedosite) . '_sitemap.xml';

        $configupdate->sitemap_data = date('Y-m-d');
        // Guardamos o caminho relativo ao disco padrão; a URL pública é gerada
        // com Storage::url() (R2 em produção, public/local em desenvolvimento).
        $configupdate->sitemap = $sitemapPath;
        $configupdate->save();

        if(Storage::disk()->exists($sitemapPath)){
            Storage::delete($sitemapPath);
        }

        $sitemap = Sitemap::create();
        $sitemap->add('/');
        $sitemap->add(Url::create('/atendimento')
        ->setLastModificationDate(Carbon::yesterday())
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
        ->setPriority(0.1));

        $paginas = Post::orderBy('created_at', 'DESC')
                    ->where('id', '!=', 5)
                    ->where('slug', '!=', 'atendimento')
                    ->where('tipo', 'pagina')
                    ->get();

        if(!empty($paginas)){
            foreach($paginas as $page):
                $sitemap->add(Url::create('/pagina/'. $page->slug));
            endforeach; 
        }

        $sitemap->add('/acomodacoes');
        $sitemap->add('/reservar');
        $sitemap->add('/politica-de-privacidade');
        $sitemap->writeToDisk(config('filesystems.default'), $sitemapPath);
        
        return response()->json(['success' => true]);
    }
    
}
