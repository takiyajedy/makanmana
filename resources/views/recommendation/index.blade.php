@php
    // Emoji ikut jenis masakan (padanan kata kunci ringkas)
    $emojiFor = function ($cuisine) {
        $c = strtolower($cuisine ?? '');
        return match (true) {
            str_contains($c, 'japan') => '🍣',
            str_contains($c, 'chinese') || str_contains($c, 'cina') => '🥢',
            str_contains($c, 'india') => '🍛',
            str_contains($c, 'western') || str_contains($c, 'grill') => '🍝',
            str_contains($c, 'cafe') || str_contains($c, 'kopitiam') || str_contains($c, 'bakery') => '☕',
            str_contains($c, 'seafood') || str_contains($c, 'steamboat') || str_contains($c, 'hotpot') => '🦐',
            str_contains($c, 'mamak') || str_contains($c, 'kandar') => '🫓',
            str_contains($c, 'street') || str_contains($c, 'hawker') => '🍢',
            str_contains($c, 'buffet') || str_contains($c, 'international') => '🍽️',
            str_contains($c, 'french') || str_contains($c, 'bistro') => '🥐',
            str_contains($c, 'bar') || str_contains($c, 'lounge') => '🍸',
            str_contains($c, 'melayu') || str_contains($c, 'kampung') => '🍚',
            default => '🍴',
        };
    };

    // Senarai jenis masakan unik untuk butang filter
    $cuisines = $restaurants->pluck('cuisine_type')->filter()->unique()->sort()->values();
@endphp

<x-app-layout>
    @push('styles')
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
        <style>
            :root {
                --brand: #ff5722;
                --brand-2: #ff8a3d;
                --brand-grad: linear-gradient(135deg, #ff8a3d 0%, #ff5722 55%, #f7411e 100%);
                --ink: #1c1714;
                --muted: #7c736d;
                --line: #efe9e4;
                --bg: #fbf7f3;
                --green: #16a34a;
                --shadow-sm: 0 1px 2px rgba(28, 23, 20, .06), 0 4px 14px rgba(28, 23, 20, .05);
            }

            body { background: var(--bg); font-family: 'Plus Jakarta Sans', 'Figtree', ui-sans-serif, system-ui, sans-serif; }

            .rec-wrap { max-width: 1180px; margin-inline: auto; padding: 28px 22px 70px; }

            /* Hero */
            .rec-hero {
                position: relative; overflow: hidden;
                background: var(--brand-grad); color: #fff;
                border-radius: 24px; padding: 36px 38px; margin-bottom: 28px;
                box-shadow: 0 24px 50px -22px rgba(247, 65, 30, .55);
            }
            .rec-hero::after {
                content: ""; position: absolute; width: 320px; height: 320px; border-radius: 50%;
                background: rgba(255, 255, 255, .12); top: -130px; right: -90px;
            }
            .rec-hero .inner { position: relative; z-index: 1; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 18px; }
            .rec-hero h1 { font-size: clamp(1.6rem, 3.2vw, 2.3rem); font-weight: 800; letter-spacing: -.02em; }
            .rec-hero p { opacity: .92; margin-top: 6px; max-width: 46ch; }
            .rec-hero .acts { display: flex; gap: 10px; flex-wrap: wrap; }
            .hbtn {
                display: inline-flex; align-items: center; gap: 8px; font-weight: 700; font-size: .95rem;
                padding: 12px 20px; border-radius: 13px; cursor: pointer; border: none; text-decoration: none;
                transition: transform .15s, box-shadow .2s, background .2s;
            }
            .hbtn-light { background: #fff; color: var(--brand); box-shadow: 0 8px 20px -8px rgba(0,0,0,.3); }
            .hbtn-light:hover { transform: translateY(-1px); }
            .hbtn-ghost { background: rgba(255,255,255,.16); color: #fff; }
            .hbtn-ghost:hover { background: rgba(255,255,255,.26); }

            /* Toolbar */
            .rec-toolbar { display: flex; flex-wrap: wrap; gap: 14px; align-items: center; margin-bottom: 22px; }
            .rec-search { position: relative; flex: 1; min-width: 240px; }
            .rec-search input {
                width: 100%; font-family: inherit; font-size: .98rem; color: var(--ink);
                background: #fff; border: 1.5px solid var(--line); border-radius: 14px;
                padding: 13px 16px 13px 44px; outline: none; transition: border-color .15s, box-shadow .15s;
            }
            .rec-search input:focus { border-color: var(--brand-2); box-shadow: 0 0 0 4px rgba(255, 138, 61, .18); }
            .rec-search .ic { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); font-size: 1.05rem; opacity: .6; }
            .rec-count { color: var(--muted); font-size: .9rem; font-weight: 600; white-space: nowrap; }

            .chips { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 26px; }
            .chip {
                font-family: inherit; font-size: .85rem; font-weight: 600; cursor: pointer;
                padding: 8px 15px; border-radius: 999px; border: 1.5px solid var(--line);
                background: #fff; color: var(--muted); transition: all .15s; white-space: nowrap;
            }
            .chip:hover { border-color: var(--brand-2); color: var(--ink); }
            .chip.active { background: var(--brand-grad); border-color: transparent; color: #fff; box-shadow: 0 8px 18px -8px rgba(247,65,30,.7); }

            /* Grid */
            .rec-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(310px, 1fr)); gap: 20px; }

            .rcard {
                background: #fff; border: 1px solid var(--line); border-radius: 20px; overflow: hidden;
                box-shadow: var(--shadow-sm); display: flex; flex-direction: column;
                transition: transform .18s, box-shadow .25s, border-color .2s;
            }
            .rcard:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -18px rgba(28,23,20,.22); border-color: #e6ddd6; }

            .rcard-top {
                position: relative; padding: 20px 20px 16px; display: flex; gap: 14px; align-items: flex-start;
                background: linear-gradient(135deg, #fff7f2, #fff);
                border-bottom: 1px solid var(--line);
            }
            .rcard-emoji {
                width: 52px; height: 52px; border-radius: 15px; flex-shrink: 0; display: grid; place-items: center;
                font-size: 1.7rem; background: linear-gradient(135deg, #fff1e9, #ffe2d2);
            }
            .rcard-title { font-size: 1.12rem; font-weight: 800; letter-spacing: -.01em; line-height: 1.2; }
            .rcard-addr { color: var(--muted); font-size: .85rem; margin-top: 4px; display: flex; gap: 5px; }
            .rcard-cuisine {
                display: inline-block; margin-top: 9px; font-size: .73rem; font-weight: 700; letter-spacing: .02em;
                color: var(--brand); background: #fff1e9; border-radius: 999px; padding: 4px 11px;
            }

            .rcard-body { padding: 16px 20px; flex: 1; }
            .rcard-body .lbl { font-size: .72rem; text-transform: uppercase; letter-spacing: .08em; color: var(--muted); font-weight: 700; margin-bottom: 10px; }
            .menu-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 8px 0; border-bottom: 1px dashed var(--line); font-size: .92rem; }
            .menu-row:last-child { border-bottom: none; }
            .menu-row .price { font-weight: 800; color: var(--green); font-size: .88rem; white-space: nowrap; }
            .menu-empty { color: var(--muted); font-style: italic; font-size: .9rem; }
            .menu-more { color: var(--muted); font-size: .82rem; font-weight: 600; margin-top: 8px; }

            .rcard-foot { padding: 0 20px 20px; }
            .rcard-btn {
                display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%;
                font-weight: 700; font-size: .92rem; text-decoration: none; cursor: pointer;
                padding: 12px; border-radius: 13px; border: 1.5px solid var(--line); background: #fff; color: var(--ink);
                transition: all .15s;
            }
            .rcard-btn:hover { background: var(--brand-grad); border-color: transparent; color: #fff; box-shadow: 0 10px 22px -10px rgba(247,65,30,.8); }

            /* Personalisasi */
            .pf-banner {
                display: flex; align-items: center; gap: 14px; flex-wrap: wrap;
                background: linear-gradient(135deg, #fff7f2, #fff1e9); border: 1px solid #ffe0cf;
                border-radius: 18px; padding: 16px 20px; margin-bottom: 22px;
            }
            .pf-banner .bic { font-size: 1.6rem; }
            .pf-banner .btxt { flex: 1; min-width: 200px; }
            .pf-banner .btxt b { font-weight: 800; }
            .pf-banner .btxt p { color: var(--muted); font-size: .9rem; margin-top: 2px; }
            .pf-banner a {
                font-weight: 700; font-size: .9rem; text-decoration: none; padding: 10px 18px; border-radius: 12px;
                background: var(--brand-grad); color: #fff; box-shadow: 0 8px 18px -8px rgba(247,65,30,.7); white-space: nowrap;
            }

            .rcard.is-match { border-color: #ffcaa8; }
            .rcard.is-match .rcard-top { background: linear-gradient(135deg, #fff1e9, #fff7f2); }
            .rcard.is-blocked { opacity: .58; }
            .match-badge {
                position: absolute; top: 14px; right: 14px; display: inline-flex; align-items: center; gap: 5px;
                font-size: .72rem; font-weight: 800; color: #fff; background: var(--brand-grad);
                border-radius: 999px; padding: 5px 11px; box-shadow: 0 6px 14px -6px rgba(247,65,30,.8);
            }
            .blocked-badge {
                position: absolute; top: 14px; right: 14px; font-size: .72rem; font-weight: 800; color: #fff;
                background: linear-gradient(135deg, #fb7185, #e11d48); border-radius: 999px; padding: 5px 11px;
            }
            .match-reasons { display: flex; flex-wrap: wrap; gap: 6px; padding: 0 20px 14px; }
            .match-reasons span { font-size: .73rem; font-weight: 600; color: var(--brand); background: #fff1e9; border-radius: 999px; padding: 3px 9px; }

            .rec-empty, .rec-none {
                text-align: center; padding: 60px 20px; color: var(--muted);
                background: #fff; border: 1px dashed var(--line); border-radius: 20px;
            }
            .rec-empty .em, .rec-none .em { font-size: 2.6rem; display: block; margin-bottom: 12px; }
            .rec-none { display: none; }

            @media (max-width: 560px) {
                .rec-hero { padding: 28px 24px; }
                .rec-grid { grid-template-columns: 1fr; }
            }
        </style>
    @endpush

    <div class="rec-wrap">
        <!-- Hero -->
        <section class="rec-hero">
            <div class="inner">
                <div>
                    <h1>Jom cari makan 🍜</h1>
                    <p>Terokai {{ $restaurants->count() }} tempat makan, atau buka peta dan biar kami pilihkan untuk anda.</p>
                </div>
                <div class="acts">
                    <a href="{{ route('recommendation.map') }}" class="hbtn hbtn-light">🗺️ Buka Peta</a>
                    {{-- <a href="{{ route('recommend.surprise') }}" class="hbtn hbtn-ghost">🎲 Kejutkan saya</a> --}}
                </div>
            </div>
        </section>

        @if ($restaurants->count())
            <!-- Banner personalisasi -->
            @if ($personalised)
                <div class="pf-banner">
                    <span class="bic">✨</span>
                    <div class="btxt">
                        <b>Disusun ikut citarasa anda</b>
                        <p>Tempat makan yang padan dengan citarasa anda diutamakan, dan yang mengandungi alahan diturunkan ke bawah.</p>
                    </div>
                    <a href="{{ route('preferences.edit') }}">Ubah citarasa</a>
                </div>
            @else
                <div class="pf-banner">
                    <span class="bic">🍽️</span>
                    <div class="btxt">
                        <b>Dapatkan cadangan ikut citarasa anda</b>
                        <p>Isi citarasa anda (makanan kegemaran, alahan, bajet) supaya kami boleh pilihkan tempat makan yang sesuai.</p>
                    </div>
                    <a href="{{ route('preferences.edit') }}">Set citarasa →</a>
                </div>
            @endif

            <!-- Toolbar -->
            <div class="rec-toolbar">
                <div class="rec-search">
                    <span class="ic">🔍</span>
                    <input type="text" id="recSearch" placeholder="Cari nama restoran atau jenis masakan…" autocomplete="off">
                </div>
                <span class="rec-count" id="recCount">{{ $restaurants->count() }} tempat makan</span>
            </div>

            <!-- Cuisine filter -->
            @if ($cuisines->count())
                <div class="chips" id="recChips">
                    <button class="chip active" data-cuisine="">Semua</button>
                    @foreach ($cuisines as $cuisine)
                        <button class="chip" data-cuisine="{{ strtolower($cuisine) }}">{{ $cuisine }}</button>
                    @endforeach
                </div>
            @endif

            <!-- Grid -->
            <div class="rec-grid" id="recGrid">
                @foreach ($restaurants as $restaurant)
                    <article class="rcard {{ $personalised && $restaurant->is_match ? 'is-match' : '' }} {{ $personalised && $restaurant->is_blocked ? 'is-blocked' : '' }}"
                             data-name="{{ strtolower($restaurant->name) }}"
                             data-cuisine="{{ strtolower($restaurant->cuisine_type ?? '') }}">
                        <div class="rcard-top">
                            @if ($personalised && $restaurant->is_blocked)
                                <span class="blocked-badge">⚠️ Ada alahan</span>
                            @elseif ($personalised && $restaurant->is_match)
                                <span class="match-badge">✨ Padan citarasa</span>
                            @endif
                            <div class="rcard-emoji">{{ $emojiFor($restaurant->cuisine_type) }}</div>
                            <div>
                                <h2 class="rcard-title">{{ $restaurant->name }}</h2>
                                <div class="rcard-addr">📍 <span>{{ $restaurant->address ?? 'Alamat tidak tersedia' }}</span></div>
                                @if ($restaurant->cuisine_type)
                                    <span class="rcard-cuisine">{{ $restaurant->cuisine_type }}</span>
                                @endif
                            </div>
                        </div>

                        @if ($personalised && !empty($restaurant->match_reasons))
                            <div class="match-reasons">
                                @foreach ($restaurant->match_reasons as $reason)
                                    <span>{{ $reason }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="rcard-body">
                            <div class="lbl">Menu popular</div>
                            @if ($restaurant->menus->count())
                                @foreach ($restaurant->menus->take(3) as $menu)
                                    <div class="menu-row">
                                        <span>{{ $menu->food_name }}</span>
                                        @if ($menu->price)
                                            <span class="price">RM{{ number_format($menu->price, 2) }}</span>
                                        @endif
                                    </div>
                                @endforeach
                                @if ($restaurant->menus->count() > 3)
                                    <div class="menu-more">+{{ $restaurant->menus->count() - 3 }} menu lagi</div>
                                @endif
                            @else
                                <p class="menu-empty">Tiada menu direkodkan.</p>
                            @endif
                        </div>

                        <div class="rcard-foot">
                            <a href="{{ route('restaurants.menu', $restaurant->id) }}" class="rcard-btn">
                                Lihat Menu Penuh →
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Tiada hasil carian -->
            <div class="rec-none" id="recNone">
                <span class="em">🔎</span>
                Tiada tempat makan sepadan dengan carian anda.
            </div>
        @else
            <div class="rec-empty">
                <span class="em">🍽️</span>
                Tiada restoran dalam senarai buat masa ini.
            </div>
        @endif
    </div>

    @push('js')
        <script>
            (function () {
                const search = document.getElementById('recSearch');
                const grid = document.getElementById('recGrid');
                const chipBox = document.getElementById('recChips');
                const countEl = document.getElementById('recCount');
                const noneEl = document.getElementById('recNone');
                if (!grid) return;

                const cards = Array.from(grid.querySelectorAll('.rcard'));
                let term = '';
                let cuisine = '';

                function apply() {
                    let visible = 0;
                    cards.forEach(card => {
                        const matchTerm = !term || card.dataset.name.includes(term) || card.dataset.cuisine.includes(term);
                        const matchCuisine = !cuisine || card.dataset.cuisine === cuisine;
                        const show = matchTerm && matchCuisine;
                        card.style.display = show ? '' : 'none';
                        if (show) visible++;
                    });
                    countEl.textContent = visible + ' tempat makan';
                    noneEl.style.display = visible === 0 ? 'block' : 'none';
                }

                search.addEventListener('input', e => { term = e.target.value.trim().toLowerCase(); apply(); });

                if (chipBox) {
                    chipBox.addEventListener('click', e => {
                        const chip = e.target.closest('.chip');
                        if (!chip) return;
                        chipBox.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
                        chip.classList.add('active');
                        cuisine = chip.dataset.cuisine;
                        apply();
                    });
                }
            })();
        </script>
    @endpush
</x-app-layout>
