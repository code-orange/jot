@foreach ($resources as $resource => $endpoints)
@include('jot::resource', ['resource' => $resource, 'endpoints' => $endpoints])
@endforeach
