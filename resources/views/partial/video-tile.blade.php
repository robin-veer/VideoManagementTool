<a href='/video/{{$video->id}}'>
    <div class='bg-white video m-2 border-primary-bottom'>
        <div class='top'>
            <div class="video-holder m-0 p-0">
                <video class="video-player img-fluid" muted poster="{{$video->thumbnail}}">
                    <source src="{{ asset($video->getGif()) }}" type="video/mp4">
                </video>
            </div>
            <span class="time bg-primary">
                {{ gmdate("i:s", $video->duration) }}
            </span>
        </div>

        <div class='bottom p-2'>
            <p>{{$video->file_name}} </p>
            <div class="d-flex flex-row">
                <p class="sub-text pr-1 mr-1 border-right">{{$video->views}} views</p>
                <span class="d-table mt-1 badge badge-pill badge-info">{{$video->category}}</span>
            </div>
        </div>
    </div>
</a>
