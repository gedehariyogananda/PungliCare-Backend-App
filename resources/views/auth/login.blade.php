<x-guest-layout>
    <div class="d-flex justify-content-center align-items-center min-vh-100 flex-column flex-md-row">

        <div class="w-100" style="max-width: 400px;">
            @if (session('success'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div class="mb-4 font-medium text-sm text-red-600">
                {{ session('error') }}
            </div>
            @endif
            <img src="/images/dishub/dishubxpunglicare.png" alt="logo" class="img-fluid mb-4 mx-auto d-block"
                style="max-width: 350px;">

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group position-relative has-icon-left mb-4">
                    <input class="form-control form-control-lg" type="email" name="email" placeholder="Email"
                        value="{{ old('email') }}">
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" class="form-control form-control-lg" name="password" placeholder="Password">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>
                <div class="form-check form-check-lg d-flex align-items-end">
                    <input class="form-check-input me-2" type="checkbox" name="remember" id="flexCheckDefault">
                    <label class="form-check-label text-gray-600" for="flexCheckDefault">
                        Keep me logged in
                    </label>
                </div>
                <button class="btn btn-primary btn-block shadow-lg mt-5">Log in</button>
            </form>
        </div>
    </div>
</x-guest-layout>