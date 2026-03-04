@extends('layouts.admin')

@section('title', 'TEST')

@section('content')
<div class="p-6">
    <h1 class="text-3xl font-bold">TEST - Si vous voyez ceci, le layout fonctionne</h1>
    <p>Nombre de catégories : {{ $categories->count() }}</p>
</div>
@endsection
