<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Maak je links kort') · Linkverkorter</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <a class="brand" href="{{ route('home') }}">link<span>kort</span></a>
            <nav class="nav">
                <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Nieuwe link</a>
                <a href="{{ route('home') }}#api">API</a>
            </nav>
        </div>
    </header>

    <main class="container">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container">Linkverkorter · demo-project gebouwd met Laravel {{ app()->version() }}</div>
    </footer>

    <script>
        // Kopieerknoppen: <button data-copy="tekst">
        document.addEventListener('click', async (event) => {
            const button = event.target.closest('[data-copy]');
            if (!button) return;
            const label = button.textContent;
            try {
                await navigator.clipboard.writeText(button.dataset.copy);
                button.textContent = 'Gekopieerd!';
            } catch {
                button.textContent = 'Kopiëren lukte niet';
            }
            setTimeout(() => (button.textContent = label), 1600);
        });
    </script>
</body>
</html>
