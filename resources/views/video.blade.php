@extends('base')

@section('header')

@endsection

@section('content')
    <div class='container-fluid'>
        <div class='row py-3'>
            <div class='col-12'>

                <div class="row">
                    <div class="col-12 col-lg-9">
                        @include('layout.video.video')
                    </div>


                    <div class="col-lg-3">
                        @include('layout.video.accordion')
                    </div>
                </div>

                <div class="col-12 col-lg-9 ">
                    <h3 class="text-body">{{$video->file_name}}</h3>
                    <p class="text-black-50">{{$video->views}} views</p>
                </div>

                @if($video->category == 'unknown')
                    <div class="row">
                        <div class='col-md-8 py-2'>
                            @include('layout.video.sortform')
                        </div>
                    </div>
                @endif
            </div>

            <div class='col-12'>
                <div class="row bg-white py-4">
                    <div class="col-12">
                        <p class="text-black-50">Similar Video's</p>
                    </div>
                    @foreach($sidebar_videos as $video)
                        <div class='col-12 col-md-3 col-lg-3 mb-3'>
                            @include('partial.video-tile', $video)
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script>
        console.log('Yolo');
    </script>
@append
