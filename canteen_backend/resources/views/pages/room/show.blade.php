@extends('layouts.room')


@section('content')
<div>
    <h1>{{ $item->schedule }}</h1>
    <p>{{ $item->description }}</p>
</div>
@endsection