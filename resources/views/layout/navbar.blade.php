<nav class="navbar navbar-expand-lg navbar-light bg-light border-primary-top">
    <a class="navbar-brand" href="/">GoPro Site</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link {{ request()->is('/show-all') ? 'active' : '' }}" href="{{route('video.index')}}">All</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link {{ request()->is('/sort') ? 'active' : '' }}"
                   href="{{route('video.sort')}}">Unsorted</a>
            </li>
        </ul>
    </div>
</nav>
