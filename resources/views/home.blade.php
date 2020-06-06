@extends('base')
@section('content')
    <div id='app' class='container-fluid'>

        <div class='row'>
            <div class="col-2 col-lg-2">
                @include('layout.video.side-menu')
            </div>
            <div id='content' class='col-10 col-lg-10'>
                @isset($recent_videos)
                    <div class='row pt-1 px-5'>
                        <h3>Recent video's</h3>
                        <div class="row">
                            @foreach($recent_videos as $video)
                                <div class='col-12 col-lg-4 p-0 '>
                                    @include('partial.video-tile', $video)
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <hr>
                @endisset

                <div class='row pt-1 px-5 '>
                    @include('layout.searchbar')
                    @forelse($videos as $video)
                        <div class='col-12 col-lg-4 p-0 '>
                            @include('partial.video-tile', $video)
                        </div>
                    @empty
                        <h1 class="text-center mt-5">There are no video's</h1>
                    @endforelse
                </div>


                @if(request()->order !== null ?? request()->time !== null)
                    <div class="content-footer row justify-content-center">
                        {{ $videos->appends(['order' => request()->order], ['time' => request()->time] )->links() }}
                    </div>

                @endif
            </div>
        </div>
        <script>
            let VideoBoxes = document.querySelectorAll('.top');

            VideoBoxes.forEach(function (box) {
                box.addEventListener('mouseenter', function () {
                    box.querySelector('.video-player').play();
                });

                box.addEventListener('mouseleave', function () {
                    box.querySelector('.video-player').pause();
                });
            });
        </script>

@endsection
