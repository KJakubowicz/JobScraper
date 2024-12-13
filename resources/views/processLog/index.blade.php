<!-- resources/views/process-logs/index.blade.php -->
@extends('layouts.basic')

@section('title', 'Logi procesów')

@section('content')
    <h1 class="mb-4">Logi procesów</h1>

    <!-- Lista logów procesów -->
    <div class="list-group">
        @foreach ($processLogList as $processLog)
            <div class="list-group-item">
                <div class="d-flex justify-content-between">
                    <!-- Po lewej stronie: informacje o procesie -->
                    <div>
                        <p class="mb-1"><strong>Id:</strong> {{ $processLog->id }}</p>
                        <p class="mb-1"><strong>Status:</strong> {{ $processLog->status }}</p>
                        <p class="mb-1"><strong>Liczba przetworzonych rekordów:</strong> {{ $processLog->records_processed }}</p>
                    </div>

                    <!-- Po prawej stronie: daty -->
                    <div class="text-end">
                        <p class="mb-0"><strong>Data dodania:</strong> 
                            {{ $processLog->created_at ? \Carbon\Carbon::parse($processLog->created_at)->format('d-m-Y H:i') : 'Brak danych' }}
                        </p>
                        <p class="mb-0"><strong>Ostatnia modyfikacja:</strong> 
                            {{ $processLog->updated_at ? \Carbon\Carbon::parse($processLog->updated_at)->format('d-m-Y H:i') : 'Brak danych' }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Paginacja (jeśli dodasz paginację w kontrolerze) -->
    {{-- {{ $processLogList->links() }} --}}
@endsection