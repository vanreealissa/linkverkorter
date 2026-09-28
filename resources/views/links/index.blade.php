@extends('layouts.app')

@section('content')
    <h1>Maak een lange link kort</h1>
    <p class="lead">Plak een link, kies eventueel een eigen code en deel de korte versie. Je ziet daarna hoe vaak erop is geklikt.</p>

    <form method="POST" action="{{ route('links.store') }}" class="card" style="margin-bottom:32px">
        @csrf
        <div class="field">
            <label for="url">Lange link</label>
            <input id="url" name="url" type="text" inputmode="url" value="{{ old('url') }}" placeholder="https://www.voorbeeld.nl/een/hele/lange/pagina" required @class(['is-invalid' => $errors->has('url')])>
            @error('url') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label for="code">Eigen code <span class="hint">(optioneel)</span></label>
            <div class="row" style="flex-wrap:nowrap">
                <span class="muted" style="white-space:nowrap">{{ preg_replace('#^https?://#', '', url('/')) }}/</span>
                <input id="code" name="code" type="text" value="{{ old('code') }}" placeholder="zomeractie" maxlength="20" @class(['is-invalid' => $errors->has('code')])>
            </div>
            <span class="hint">Letters, cijfers, - en _. Laat leeg voor een willekeurige code.</span>
            @error('code') <span class="error">{{ $message }}</span> @enderror
        </div>
        <button class="btn" type="submit">Verkort link</button>
    </form>

    <h2>Recente links</h2>
    <div class="card table-wrap" style="margin-bottom:40px">
        @if ($links->isEmpty())
            <p class="muted" style="margin:0">Nog geen links. Maak hierboven je eerste korte link.</p>
        @else
            <table>
                <thead><tr><th>Korte link</th><th>Gaat naar</th><th class="num">Kliks</th><th></th></tr></thead>
                <tbody>
                    @foreach ($links as $link)
                        <tr>
                            <td><a href="{{ route('links.show', $link) }}">/{{ $link->code }}</a></td>
                            <td class="muted" style="word-break:break-all">{{ $link->displayUrl() }}</td>
                            <td class="num">{{ $link->clicks_count }}</td>
                            <td><button class="btn small secondary" type="button" data-copy="{{ $link->shortUrl() }}">Kopieer</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <section id="api" class="card">
        <h2>API</h2>
        <p class="muted">Maak links aan vanuit je eigen code. Maximaal 30 verzoeken per minuut.</p>
        <pre class="code">curl -X POST {{ url('/api/links') }} \
  -H "Accept: application/json" \
  -d url=https://laravel.com/docs \
  -d code=docs</pre>
        <p class="muted">Antwoord (201):</p>
        <pre class="code">{
  "data": {
    "code": "docs",
    "url": "https://laravel.com/docs",
    "short_url": "{{ url('docs') }}",
    "clicks": 0,
    "created_at": "2026-09-28T12:00:00+02:00"
  }
}</pre>
        <p class="muted" style="margin-bottom:0">Statistieken opvragen: <code>GET {{ url('/api/links/{code}') }}</code></p>
    </section>
@endsection
