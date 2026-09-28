<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLinkRequest;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LinkController extends Controller
{
    public function index()
    {
        return view('links.index', [
            'links' => Link::latest()->limit(10)->get(),
        ]);
    }

    public function store(StoreLinkRequest $request)
    {
        $link = Link::create([
            'url' => $request->validated('url'),
            'code' => $request->validated('code') ?? Link::generateCode(),
        ]);

        return redirect()->route('links.show', $link)->with('created', true);
    }

    public function show(Link $link)
    {
        $days = collect(range(13, 0))->map(fn (int $i) => today()->subDays($i));

        $clicks = $link->clicks()->where('created_at', '>=', $days->first())->get();
        $perDay = $clicks->countBy(fn ($click) => $click->created_at->toDateString());

        $chart = $days->map(fn (Carbon $day) => [
            'date' => $day,
            'count' => $perDay->get($day->toDateString(), 0),
        ]);

        return view('links.show', [
            'link' => $link,
            'chart' => $chart,
            'max' => max(1, $chart->max('count')),
            'referrers' => $link->clicks()
                ->selectRaw('referrer_host, count(*) as total')
                ->groupBy('referrer_host')
                ->orderByDesc('total')
                ->limit(5)
                ->get(),
        ]);
    }

    public function redirect(Request $request, Link $link)
    {
        $link->clicks()->create([
            'referrer_host' => parse_url((string) $request->headers->get('referer'), PHP_URL_HOST) ?: null,
        ]);
        $link->increment('clicks_count');

        return redirect()->away($link->url);
    }
}
