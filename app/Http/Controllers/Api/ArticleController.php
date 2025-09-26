<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserPreference;
use App\Repositories\ArticleRepositoryInterface;
use App\Models\Article;

class ArticleController extends Controller
{
    protected $repo;

    public function __construct(ArticleRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['q','source','category','date_from','date_to','sort']);
        $perPage = (int) $request->get('per_page', 15);
        $articles = $this->repo->search($filters, $perPage);
        return response()->json($articles);
    }

    public function show($id)
    {
        $article = Article::findOrFail($id);
        return response()->json($article);
    }

   public function sources() {
        return response()->json(
            Article::select('source_code')->distinct()->pluck('source_code')
        );
    }
  public function savePreferences(Request $request) {
        $data = $request->validate([
            'user_id'    => 'nullable|integer',
            'sources'    => 'nullable|array',
            'categories' => 'nullable|array',
            'authors'    => 'nullable|array',
        ]);

        $pref = UserPreference::updateOrCreate(
            ['user_id' => $data['user_id'] ?? null],
            [
                'sources'    => $data['sources'] ?? [],
                'categories' => $data['categories'] ?? [],
                'authors'    => $data['authors'] ?? []
            ]
        );

        return response()->json($pref, 200);
    }
    public function savePreferences1(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'nullable|integer',
            'sources' => 'nullable|array',
            'categories' => 'nullable|array',
            'authors' => 'nullable|array',
        ]);
        $pref = UserPreference::updateOrCreate(
            ['user_id' => $data['user_id'] ?? null],
            ['sources' => $data['sources'] ?? [], 'categories' => $data['categories'] ?? [], 'authors' => $data['authors'] ?? []]
        );
        return response()->json($pref);
    }

}
