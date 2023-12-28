@php
    use App\Enums\DemandStatus;
@endphp
@extends('templates.admin')
@section('title', 'Página Inicial')
@section('breadcrumb')
    <vstack-breadcrumb :items="[{
        route: '/admin',
        title: 'Página Inicial'
    }, ]">
    </vstack-breadcrumb>
@endsection
@section('content')
    <dashboard-comp></dashboard-comp>
    {{-- <x-vstack-component callback='\App\Http\Resources\AccessGroups@testComponent' :payload="['value' => 'Sync']" /> --}}
    {{-- <vstack-async-component callback='\App\Http\Resources\AccessGroups@testComponent' :payload='@json(['value' => 'Async'])' :timeout="4000">
        carregando ...
    </vstack-async-component> --}}
    <h3 class="text-3xl text-neutral-800 font-bold dark:text-neutral-200 mt-5">
        {{ SayGoodMorning() }}, {{ $user->firstName }}!
    </h3>
@endsection
