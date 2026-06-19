{{-- PWA: manifest, tema, ikon, dan pendaftaran service worker --}}
<meta name="theme-color" content="#ff5722">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="MakanMana">
<link rel="manifest" href="{{ asset('manifest.json') }}">
<link rel="icon" href="{{ asset('icons/icon.svg') }}" type="image/svg+xml">
<link rel="apple-touch-icon" href="{{ asset('icons/icon.svg') }}">
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('{{ asset('sw.js') }}', { scope: '/' }).catch(function () {});
        });
    }
</script>
