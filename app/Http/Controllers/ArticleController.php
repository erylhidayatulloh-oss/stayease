<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::published()
            ->with('author')
            ->latest('published_at')
            ->paginate(9);

        return view('articles.index', compact('articles'));
    }

    public function show(Article $article)
    {
        if ($article->status !== 'published' && !(auth()->check() && auth()->user()->isAdmin())) {
            abort(404);
        }

        $related = Article::published()
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('articles.show', compact('article', 'related'));
    }
}
