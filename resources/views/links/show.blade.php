@extends('layouts.app')

@section('title', '/'.$link->code)

@section('content')
    @if (session('created'))
        <div class="alert success" role="status">Je korte link is klaar. Kopieer hem en deel hem waar je wilt.</div>
    @endif

    <div class="card stack" style="margin-bottom:24px">
        <div class="row between">
            <div>
                <p class="muted" style="margin:0">Korte link</p>
                <a href="{{ $link->shortUrl() }}" target="_blank" rel="noopener" style="font-size:1.6rem;font-weight:700;word-break:break-all">{{ preg_replace('#^https?://#', '', $link->shortUrl()) }}</a>
            </div>
            <button class="btn" type="button" data-copy="{{ $link->shortUrl() }}">Kopieer link</button>
        </div>
        <p style="margin:0;word-break:break-all"><span class="muted">Gaat naar</span> <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer">{{ $link->url }}</a></p>
    </div>

    <div class="grid" style="margin-bottom:24px">
        <div class="card"><p class="muted" style="margin:0">Kliks in totaal</p><strong class="num" style="font-size:2rem">{{ $link->clicks_count }}</strong></div>
        <div class="card"><p class="muted" style="margin:0">Laatste 14 dagen</p><strong class="num" style="font-size:2rem">{{ $chart->sum('count') }}</strong></div>
        <div class="card"><p class="muted" style="margin:0">Aangemaakt</p><strong style="font-size:1.2rem">{{ $link->created_at->translatedFormat('j F Y') }}</strong></div>
    </div>

    <section class="card" style="margin-bottom:24px">
        <h2>Kliks per dag</h2>
        <div class="bars" role="img" aria-label="Staafdiagram van het aantal kliks per dag over de laatste 14 dagen">
            @foreach ($chart as $day)
                <div class="bar" title="{{ $day['date']->translatedFormat('j M') }}: {{ $day['count'] }} kliks">
                    <span class="bar-value num">{{ $day['count'] ?: '' }}</span>
                    <span class="bar-fill" style="height: {{ round($day['count'] / $max * 100) }}%"></span>
                    <span class="bar-label">{{ $day['date']->format('j') }}</span>
                </div>
            @endforeach
        </div>
    </section>

    <section class="card">
        <h2>Waar komen de kliks vandaan?</h2>
        @if ($referrers->isEmpty())
            <p class="muted" style="margin:0">Nog geen kliks. Deel je link en kijk hier terug.</p>
        @else
            <table>
                <tbody>
                    @foreach ($referrers as $row)
                        <tr><td>{{ $row->referrer_host ?? 'Direct of onbekend' }}</td><td class="num" style="text-align:right">{{ $row->total }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>
@endsection
