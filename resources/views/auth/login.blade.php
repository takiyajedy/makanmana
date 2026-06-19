<x-guest-layout>
    <a href="{{ route('landing') }}" class="auth-back">← Kembali ke laman utama</a>

    <div class="auth-head">
        <h1>Selamat kembali 👋</h1>
        <p>Log masuk untuk teruskan mencari tempat makan.</p>
    </div>

    @if (session('status'))
        <div class="auth-status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="auth-field">
            <label class="auth-label" for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="auth-input @error('email') has-error @enderror"
                   placeholder="nama@email.com" required autofocus autocomplete="username">
            @error('email')
                <div class="auth-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="auth-field">
            <label class="auth-label" for="password">Kata laluan</label>
            <input id="password" type="password" name="password"
                   class="auth-input @error('password') has-error @enderror"
                   placeholder="••••••••" required autocomplete="current-password">
            @error('password')
                <div class="auth-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="auth-row">
            <label class="auth-check" for="remember_me">
                <input id="remember_me" type="checkbox" name="remember">
                Ingat saya
            </label>

            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">Lupa kata laluan?</a>
            @endif
        </div>

        <button type="submit" class="auth-btn">Log masuk</button>
    </form>

    <p class="auth-foot">
        Belum ada akaun? <a href="{{ route('register') }}">Daftar percuma</a>
    </p>
</x-guest-layout>
