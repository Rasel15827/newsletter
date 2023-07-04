    <?php
    use Illuminate\Support\Facades\Route;
    $routeName = Route::currentRouteName();
    ?>
    <!-- Navbar -->
     <nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 w-100 shadow-none my-3 navbar-transparent mt-4">
    <div class="container ps-2 pe-0">
      <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 text-white" href="#">
      {{ config('app.name') }}
      </a>
      <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon mt-2">
          <span class="navbar-toggler-bar bar1"></span>
          <span class="navbar-toggler-bar bar2"></span>
          <span class="navbar-toggler-bar bar3"></span>
        </span>
      </button>
      <div class="collapse navbar-collapse w-100 pt-3 pb-2 py-lg-0" id="navigation">
        <ul class="navbar-nav navbar-nav-hover mx-auto">
        </ul>
      </div>
    </div>
  </nav>