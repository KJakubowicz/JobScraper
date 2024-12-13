@extends('layouts.basic')

@section('title', 'Oferty pracy')

@section('content')
    <h1 class="mb-4">Oferty pracy</h1>
    <form method="GET" action="{{ route('job-offers.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="job_title" class="form-label">Tytuł pracy</label>
                <input type="text" name="job_title" id="job_title" class="form-control" value="{{ request('job_title') }}">
            </div>
            <div class="col-md-3">
                <label for="work_mode" class="form-label">Typ pracy</label>
                <select name="work_mode" id="work_mode" class="form-select">
                    <option value="">Wybierz typ</option>
                    <option value="Hybrid" {{ request('work_mode') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                    <option value="On-site" {{ request('work_mode') == 'On-site' ? 'selected' : '' }}>On-site</option>
                    <option value="Remote" {{ request('work_mode') == 'Remote' ? 'selected' : '' }}>Remote</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="work_type" class="form-label">Rodzaj pracy</label>
                <select name="work_type" id="work_type" class="form-select">
                    <option value="">Wybierz rodzaj</option>
                    <option value="Full time" {{ request('work_type') == 'Full time' ? 'selected' : '' }}>Full time</option>
                    <option value="Temporary" {{ request('work_type') == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                    <option value="Contract" {{ request('work_type') == 'Contract' ? 'selected' : '' }}>Contract</option>
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Filtruj</button>
    </form>
    <div class="list-group">
        @foreach ($jobOffers as $offer)
            <div class="list-group-item">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="mb-1"><strong>Tytuł pracy:</strong> {{ $offer['version']->job_title }}</p>
                        <p class="mb-1"><strong>Lokalizacja:</strong> {{ $offer['version']->location }}</p>
                        <p class="mb-1"><strong>Typ pracy:</strong> {{ $offer['version']->work_mode }}</p>
                        <p class="mb-1"><strong>Rodzaj pracy:</strong> {{ $offer['version']->work_type }}</p>
                    </div>
                    <div class="text-end">
                        <p class="mb-0"><strong>Data dodania:</strong> 
                            {{ $offer['jobOffer']->created_at ? \Carbon\Carbon::parse($offer['jobOffer']->created_at)->format('d-m-Y H:i') : 'Brak danych' }}
                        </p>
                        <p class="mb-0"><strong>Ostatnia modyfikacja:</strong> 
                            {{ $offer['version']->created_at ? \Carbon\Carbon::parse($offer['version']->created_at)->format('d-m-Y H:i') : 'Brak danych' }}
                        </p>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ $offer['jobOffer']->url }}" class="btn btn-link" target="_blank">Zobacz ofertę</a>
                    <a href="{{ route('job-offers.history', $offer['jobOffer']->id) }}" class="btn btn-link">Historia zmian</a>
                </div>

            </div>
        @endforeach
    </div>
@endsection