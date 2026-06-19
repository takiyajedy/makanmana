@props([])

<span {{ $attributes->merge(['class' => 'mm-logo']) }}
      style="display:inline-flex;align-items:center;gap:9px;font-family:'Plus Jakarta Sans','Figtree',ui-sans-serif,system-ui,sans-serif;font-weight:800;font-size:1.2rem;letter-spacing:-.02em;color:#1c1714;line-height:1">
    <span style="width:36px;height:36px;border-radius:11px;display:grid;place-items:center;
                 background:linear-gradient(135deg,#ff8a3d 0%,#ff5722 55%,#f7411e 100%);
                 box-shadow:0 6px 16px -4px rgba(247,65,30,.6);font-size:1.1rem">🍽️</span>
    <span>Makan<span style="color:#ff5722">Mana</span></span>
</span>
