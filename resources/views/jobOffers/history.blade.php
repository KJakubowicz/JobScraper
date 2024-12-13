@extends('layouts.basic')

@section('title', 'Historia oferty pracy')

@section('content')
<div class=" p-3 mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Historia oferty</h1>
        <a href="/" class="btn btn-primary">
            Powrót
        </a>
    </div>
</div>
    <div class="list-group">
        @foreach ($versionList as $version)
            <div class="list-group-item {{ $version->is_active ? 'border border-primary' : '' }}">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="mb-1"><strong>Tytuł pracy:</strong> {{ $version->job_title }}</p>
                        <p class="mb-1"><strong>Lokalizacja:</strong> {{ $version->location }}</p>
                        <p class="mb-1"><strong>Typ pracy:</strong> {{ $version->work_mode }}</p>
                        <p class="mb-1"><strong>Rodzaj pracy:</strong> {{ $version->work_type }}</p>
                    </div>
                    <div class="text-end">
                        <p class="mb-0"><strong>Data utworzenia:</strong> 
                            {{ $version->created_at ? \Carbon\Carbon::parse($version->created_at)->format('d-m-Y H:i') : 'Brak danych' }}
                        </p>
                    </div>
                </div>
                
                @if (!$version->is_active)
                    <form action="{{ route('job-offers-version.set-active-version', ['versionId' => $version->id]) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Ustaw jako aktywną wersję</button>
                    </form>
                @endif
                <form action="{{ route('job-offers-version.delete-version', ['versionId' => $version->id]) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Usuń</button>
                </form>
            </div>
        @endforeach
    </div>
@endsection