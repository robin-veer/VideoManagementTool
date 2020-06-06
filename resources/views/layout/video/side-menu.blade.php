<ul class="list-unstyled">

    @foreach($tags as $tag)
        <li class="my-2">
            @isset(request()->tag)
            <a href="{{request()->tag.'+'.$tag->name}}">+</a>
            @endisset
            <a href="{{route('tag.show', $tag->name)}}">{{$tag->name}}</a>
            <span class="my-auto d-inline-block float-right badge bg-primary-light text-white">
                {{$tag->videos_count}}
            </span>
        </li>
    @endforeach
</ul>
