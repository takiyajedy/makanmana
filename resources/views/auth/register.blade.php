<x-guest-layout>
    <a href="{{ route('landing') }}" class="auth-back">← Kembali ke laman utama</a>

    <div class="auth-head">
        <h1>Cipta akaun 🍴</h1>
        <p>Daftar percuma dan mula temui tempat makan terbaik.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="auth-field">
            <label class="auth-label" for="name">Nama</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   class="auth-input @error('name') has-error @enderror"
                   placeholder="Nama penuh anda" required autofocus autocomplete="name">
            @error('name')
                <div class="auth-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="auth-field">
            <label class="auth-label" for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="auth-input @error('email') has-error @enderror"
                   placeholder="nama@email.com" required autocomplete="username">
            @error('email')
                <div class="auth-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="auth-field">
            <label class="auth-label" for="password">Kata laluan</label>
            <input id="password" type="password" name="password"
                   class="auth-input @error('password') has-error @enderror"
                   placeholder="Minimum 8 aksara" required autocomplete="new-password">
            @error('password')
                <div class="auth-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="auth-field">
            <label class="auth-label" for="password_confirmation">Sahkan kata laluan</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="auth-input @error('password_confirmation') has-error @enderror"
                   placeholder="Ulang kata laluan" required autocomplete="new-password">
            @error('password_confirmation')
                <div class="auth-error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="auth-btn">Daftar sekarang</button>
    </form>

    <p class="auth-foot">
        Sudah ada akaun? <a href="{{ route('login') }}">Log masuk</a>
    </p>
</x-guest-layout>
