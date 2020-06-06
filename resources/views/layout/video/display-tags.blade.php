<div class="bg-white p-2">
    <p class="text-black-50">Tags</p>
    @foreach($video->tags as $tag)
        <a href="{{route('tag.show',$tag->name)}}"> <span
                class="badge badge-dark p-2 my-0 mx-auto">{{$tag->name}}</span></a>
    @endforeach
    @empty(count($video->tags))
        <span class="badge badge-light p-2 my-0 mx-auto">No tags yet</span>
    @endempty
</div>
