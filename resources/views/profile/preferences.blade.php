@php
    $fav = $user->favorite_foods ?? [];
    $alg = $user->food_allergies ?? [];
    $cui = $user->preferred_cuisines ?? [];
    $diet = $user->dietary ?? [];

    // Item teks bebas = yang disimpan tapi tiada dalam senarai tetap
    $favCustom = implode(', ', array_diff($fav, $foods));
    $algCustom = implode(', ', array_diff($alg, $allergies));

    $foodEmoji = ['Nasi' => '🍚', 'Mee' => '🍜', 'Roti' => '🍞', 'Ayam' => '🍗', 'Daging' => '🥩', 'Ikan' => '🐟', 'Sayur' => '🥦', 'Telur' => '🥚', 'Sup' => '🍲', 'Burger' => '🍔', 'Pizza' => '🍕', 'Sushi' => '🍣'];
    $spicyLabels = [0 => '🚫 Tak suka', 1 => '🌶️ Sederhana', 2 => '🌶️🌶️ Pedas', 3 => '🔥 Sangat pedas'];
    $budgetLabels = ['jimat' => ['💸', 'Jimat', '≤ RM15'], 'sederhana' => ['💵', 'Sederhana', 'RM15–40'], 'mewah' => ['💎', 'Mewah', '> RM40']];
@endphp

<x-app-layout>
    @push('styles')
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
        <style>
            :root {
                --brand: #ff5722; --brand-2: #ff8a3d;
                --brand-grad: linear-gradient(135deg, #ff8a3d 0%, #ff5722 55%, #f7411e 100%);
                --ink: #1c1714; --muted: #7c736d; --line: #efe9e4; --bg: #fbf7f3;
                --green: #16a34a; --red: #e11d48;
                --shadow-sm: 0 1px 2px rgba(28, 23, 20, .06), 0 4px 14px rgba(28, 23, 20, .05);
            }
            body { background: var(--bg); font-family: 'Plus Jakarta Sans', 'Figtree', ui-sans-serif, system-ui, sans-serif; }

            .pf-wrap { max-width: 860px; margin-inline: auto; padding: 28px 22px 80px; }

            .pf-hero {
                position: relative; overflow: hidden; background: var(--brand-grad); color: #fff;
                border-radius: 24px; padding: 34px 36px; margin-bottom: 24px;
                box-shadow: 0 24px 50px -22px rgba(247, 65, 30, .55);
            }
            .pf-hero::after { content: ""; position: absolute; width: 300px; height: 300px; border-radius: 50%; background: rgba(255,255,255,.12); top: -120px; right: -80px; }
            .pf-hero h1 { position: relative; z-index: 1; font-size: clamp(1.6rem, 3.2vw, 2.2rem); font-weight: 800; letter-spacing: -.02em; }
            .pf-hero p { position: relative; z-index: 1; opacity: .92; margin-top: 6px; max-width: 52ch; }

            .pf-status {
                display: flex; align-items: center; gap: 10px; background: #ecfdf3; border: 1px solid #bbf7d0;
                color: #15803d; border-radius: 14px; padding: 13px 16px; font-weight: 600; margin-bottom: 22px;
            }

            .pf-card { background: #fff; border: 1px solid var(--line); border-radius: 20px; padding: 26px; margin-bottom: 20px; box-shadow: var(--shadow-sm); }
            .pf-card h2 { font-size: 1.15rem; font-weight: 800; letter-spacing: -.01em; display: flex; align-items: center; gap: 9px; }
            .pf-card .hint { color: var(--muted); font-size: .9rem; margin-top: 3px; margin-bottom: 16px; }

            .pchips { display: flex; flex-wrap: wrap; gap: 10px; }
            .pchip { cursor: pointer; user-select: none; }
            .pchip input { position: absolute; opacity: 0; width: 0; height: 0; }
            .pchip span {
                display: inline-flex; align-items: center; gap: 7px; font-size: .92rem; font-weight: 600;
                padding: 10px 16px; border-radius: 999px; border: 1.5px solid var(--line); background: #fff; color: var(--muted);
                transition: all .15s;
            }
            .pchip:hover span { border-color: var(--brand-2); color: var(--ink); }
            .pchip input:checked + span { background: var(--brand-grad); border-color: transparent; color: #fff; box-shadow: 0 8px 18px -8px rgba(247,65,30,.7); }
            .pchip.danger input:checked + span { background: linear-gradient(135deg, #fb7185, #e11d48); box-shadow: 0 8px 18px -8px rgba(225,29,72,.7); }

            .pf-custom { margin-top: 14px; }
            .pf-custom label { display: block; font-size: .82rem; font-weight: 600; color: var(--muted); margin-bottom: 6px; }
            .pf-custom input {
                width: 100%; font-family: inherit; font-size: .95rem; color: var(--ink); background: var(--bg);
                border: 1.5px solid var(--line); border-radius: 12px; padding: 11px 14px; outline: none; transition: border-color .15s, box-shadow .15s;
            }
            .pf-custom input:focus { border-color: var(--brand-2); box-shadow: 0 0 0 4px rgba(255,138,61,.18); background: #fff; }

            /* Segmented (spicy / budget) */
            .seg { display: flex; flex-wrap: wrap; gap: 10px; }
            .seg label { cursor: pointer; flex: 1; min-width: 130px; }
            .seg input { position: absolute; opacity: 0; }
            .seg .opt {
                display: flex; flex-direction: column; align-items: center; gap: 2px; text-align: center;
                padding: 16px 12px; border-radius: 15px; border: 1.5px solid var(--line); background: #fff;
                font-weight: 700; transition: all .15s;
            }
            .seg .opt .sub { font-size: .78rem; font-weight: 500; color: var(--muted); }
            .seg label:hover .opt { border-color: var(--brand-2); }
            .seg input:checked + .opt { border-color: var(--brand); background: #fff7f3; box-shadow: 0 8px 18px -10px rgba(247,65,30,.5); }
            .seg input:checked + .opt .sub { color: var(--brand); }

            .pf-actions { display: flex; align-items: center; gap: 14px; margin-top: 6px; }
            .pf-save {
                display: inline-flex; align-items: center; gap: 8px; font-family: inherit; font-weight: 700; font-size: 1rem;
                cursor: pointer; border: none; border-radius: 14px; padding: 14px 30px; color: #fff; background: var(--brand-grad);
                box-shadow: 0 12px 26px -10px rgba(247, 65, 30, .8); transition: transform .15s, box-shadow .2s;
            }
            .pf-save:hover { transform: translateY(-1px); box-shadow: 0 16px 32px -8px rgba(247, 65, 30, .9); }
            .pf-clear { font-weight: 600; color: var(--muted); text-decoration: none; }
            .pf-clear:hover { color: var(--ink); }
        </style>
    @endpush

    <div class="pf-wrap">
        <section class="pf-hero">
            <h1>Citarasa anda 😋</h1>
            <p>Beritahu kami apa yang anda suka &amp; elak. Bila anda tekan "Pilihkan untuk saya", sistem akan
                utamakan tempat makan yang padan dengan citarasa ini.</p>
        </section>

        @if (session('status') === 'preferences-updated')
            <div class="pf-status">✅ Citarasa anda telah disimpan!</div>
        @endif

        <form method="POST" action="{{ route('preferences.update') }}">
            @csrf
            @method('PATCH')

            <!-- Makanan kegemaran -->
            <div class="pf-card">
                <h2>🍽️ Makanan kegemaran</h2>
                <p class="hint">Pilih makanan yang anda paling suka. Kami akan cari menu yang sepadan.</p>
                <div class="pchips">
                    @foreach ($foods as $food)
                        <label class="pchip">
                            <input type="checkbox" name="favorite_foods[]" value="{{ $food }}" @checked(in_array($food, $fav))>
                            <span>{{ $foodEmoji[$food] ?? '🍴' }} {{ $food }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="pf-custom">
                    <label for="favorite_custom">Lain-lain (pisahkan dengan koma)</label>
                    <input type="text" id="favorite_custom" name="favorite_custom"
                           value="{{ old('favorite_custom', $favCustom) }}"
                           placeholder="cth: Tomyam, Nasi Lemak, Char Kuey Teow">
                </div>
            </div>

            <!-- Jenis masakan -->
            <div class="pf-card">
                <h2>🌏 Jenis masakan kegemaran</h2>
                <p class="hint">Restoran dengan jenis masakan ini akan diutamakan.</p>
                <div class="pchips">
                    @foreach ($cuisines as $cuisine)
                        <label class="pchip">
                            <input type="checkbox" name="preferred_cuisines[]" value="{{ $cuisine }}" @checked(in_array($cuisine, $cui))>
                            <span>{{ $cuisine }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Tahap pedas -->
            <div class="pf-card">
                <h2>🌶️ Tahap pedas</h2>
                <p class="hint">Sejauh mana anda boleh terima pedas?</p>
                <div class="seg">
                    @foreach ($spicyLabels as $lvl => $label)
                        <label>
                            <input type="radio" name="spicy_level" value="{{ $lvl }}" @checked((string) $user->spicy_level === (string) $lvl)>
                            <span class="opt">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Bajet -->
            <div class="pf-card">
                <h2>💰 Bajet biasa</h2>
                <p class="hint">Anggaran perbelanjaan setiap hidangan.</p>
                <div class="seg">
                    @foreach ($budgetLabels as $key => [$ic, $title, $sub])
                        <label>
                            <input type="radio" name="budget" value="{{ $key }}" @checked($user->budget === $key)>
                            <span class="opt">{{ $ic }} {{ $title }}<span class="sub">{{ $sub }}</span></span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Alahan -->
            <div class="pf-card">
                <h2>⚠️ Alahan / makanan dielak</h2>
                <p class="hint">Tempat makan dengan bahan ini <strong>tidak akan</strong> dicadangkan kepada anda.</p>
                <div class="pchips">
                    @foreach ($allergies as $allergy)
                        <label class="pchip danger">
                            <input type="checkbox" name="food_allergies[]" value="{{ $allergy }}" @checked(in_array($allergy, $alg))>
                            <span>{{ $allergy }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="pf-custom">
                    <label for="allergy_custom">Lain-lain (pisahkan dengan koma)</label>
                    <input type="text" id="allergy_custom" name="allergy_custom"
                           value="{{ old('allergy_custom', $algCustom) }}"
                           placeholder="cth: Cili, Belacan">
                </div>
            </div>

            <!-- Pemakanan -->
            <div class="pf-card">
                <h2>🥗 Keperluan pemakanan</h2>
                <p class="hint">Pilihan diet atau halal.</p>
                <div class="pchips">
                    @foreach ($dietary as $d)
                        <label class="pchip">
                            <input type="checkbox" name="dietary[]" value="{{ $d }}" @checked(in_array($d, $diet))>
                            <span>{{ $d }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="pf-actions">
                <button type="submit" class="pf-save">💾 Simpan citarasa</button>
                <a href="{{ route('recommend.index') }}" class="pf-clear">Lihat cadangan →</a>
            </div>
        </form>
    </div>
</x-app-layout>
