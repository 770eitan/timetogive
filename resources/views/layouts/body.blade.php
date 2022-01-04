<body>
  @if (isHomePage())
  <div class="container-full mb-5">
    <div class="bg-dark text-secondary px-4 py-5 text-center">
      <div class="py-5">
        <h1 class="display-5 fw-bold text-white">Dark mode hero</h1>
        <div class="col-lg-6 mx-auto">
          <p class="fs-5 mb-4">Quickly design and customize responsive mobile-first sites with Bootstrap, the world’s most popular front-end open source toolkit, featuring Sass variables and mixins, responsive grid system, extensive prebuilt components, and powerful JavaScript plugins.</p>
          <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a href="{{ route('home').'#search' }}" class="btn btn-outline-info btn-lg px-4 me-sm-3 fw-bold" tabindex="-1" role="button" aria-disabled="true">Start Now</a>
            <a href="{{ route('home').'#payment' }}" class="btn btn-outline-light btn-lg px-4" tabindex="-1" role="button" aria-disabled="true">Check up Mine</a>
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