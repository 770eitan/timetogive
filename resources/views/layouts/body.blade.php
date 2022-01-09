<body>
  @if (isHomePage())
  <div class="container-full mb-5">
    <div class="bg-dark text-secondary px-4 py-5 text-center">
      <div class="py-5">
        <h1 class="display-5 fw-bold text-white">Welcome to Time to Give!</h1>
        <div class="col-lg-6 mx-auto">
          <p class="fs-5 mb-4">A revolutionary tool to give non-stop charity - your specific amounts in your specific intervals for your specific time span. “Tzedoka hastens the Geulah.” For the first time in history, we are able to fulfill the Mitzva of Tzedoka the most possible number of times in a given time period!</p>
          <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a href="{{ route('home').'#payment' }}" class="btn btn-outline-info btn-lg px-4 me-sm-3 fw-bold" tabindex="-1" role="button" aria-disabled="true">Start Now</a>
            <a href="{{ route('home').'#search' }}" class="btn btn-outline-light btn-lg px-4" tabindex="-1" role="button" aria-disabled="true">Check up Mine</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif
  <main class="container">
    @yield('content')
    @include('layouts.footer')
  </main>
</body>