@extends('layouts.app')

@section('title','Dashboard')

@section('content')
    <ul class="nav nav-tabs mb-4" id="dashboardTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="utenti-tab" data-bs-toggle="tab" data-bs-target="#utenti" type="button">
                Utenti
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="periodi-tab" data-bs-toggle="tab" data-bs-target="#periodi" type="button">
                Periodi
            </button>
        </li>
    </ul>

    <div class="tab-content" id="dashboardTabContent">

        {{-- UTENTI TAB --}}
        <div class="tab-pane fade show active" id="utenti" role="tabpanel">
            <div class="row g-4">

                {{-- Utenti registrati --}}
                <div class="col-12 col-lg-4">
                    <div class="card text-white bg-primary h-100">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <h5 class="card-title">Utenti registrati</h5>
                            <h1 class="display-1">{{ $totalUsers }}</h1>
                        </div>
                    </div>
                </div>

                {{-- Tipo intervento --}}
                <div class="col-12 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-3">Tipo intervento</h6>
                            <canvas id="surgeryTypeChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Mobilità iniziale --}}
                <div class="col-12 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-3">Mobilità iniziale</h6>
                            <canvas id="movementChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="row g-4">
                        {{-- Distribuzione fasce d'età --}}
                        <div class="col-12 col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Distribuzione fasce d'età</h5>
                                    <canvas id="ageChart"></canvas>
                                </div>
                            </div>
                        </div>

                        {{-- Tempo dall'intervento --}}
                        <div class="col-12 col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-3">Tempo dall'intervento</h6>
                                    <canvas id="surgeryTimeChart"></canvas>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        {{-- PERIODI TAB --}}
        <div class="tab-pane fade" id="periodi" role="tabpanel">
            <div class="row g-4">
                @foreach($periods as $idx => $p)
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-secondary text-white fw-bold">
                                {{ $p['title'] }}
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    {{-- colonna di sinistra --}}
                                    <div class="col-lg-4 mb-3">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Utenti attivi
                                                <span class="badge bg-primary">{{ $p['users'] }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Sessioni totali
                                                <span class="badge bg-info">{{ $p['sessions']['total'] }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Sessioni completate
                                                <span class="badge bg-success">{{ $p['sessions']['completed'] }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Questionari inviati
                                                <span class="badge bg-warning">{{ $p['questionnaires'] }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Media sessioni/giorno per utente
                                                <span class="badge bg-dark">{{ $p['avg_daily_sessions'] }}</span>
                                            </li>
                                        </ul>
                                    </div>

                                    {{-- accordion domande (invariato, ma usa 'distribution' filtrata) --}}
                                    <div class="col-lg-8">
                                        <div class="accordion" id="accordionPeriod{{ $idx }}">
                                            @foreach($p['questions'] as $j => $q)
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="heading{{ $idx }}{{ $j }}">
                                                        <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapse{{ $idx }}{{ $j }}"
                                                                aria-expanded="false"
                                                                aria-controls="collapse{{ $idx }}{{ $j }}">
                                                            {{ $q['title'] }}
                                                            <small class="text-muted ms-2">({{ $q['question'] }})</small>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse{{ $idx }}{{ $j }}"
                                                         class="accordion-collapse collapse"
                                                         aria-labelledby="heading{{ $idx }}{{ $j }}"
                                                         data-bs-parent="#accordionPeriod{{ $idx }}">
                                                        <div class="accordion-body p-0">
                                                            <table class="table table-borderless mb-0">
                                                                <thead class="table-light">
                                                                <tr>
                                                                    <th>Risposta</th>
                                                                    <th class="text-end">Conteggio</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @if($q['type'] === 'choice')
                                                                    @foreach($q['options'] as $opt)
                                                                        <tr>
                                                                            <td>{{ $opt }}</td>
                                                                            <td class="text-end">{{ $q['distribution'][$opt] ?? 0 }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    @for($n = 1; $n <= 10; $n++)
                                                                        <tr>
                                                                            <td>{{ $n }}</td>
                                                                            <td class="text-end">{{ $q['distribution'][$n] ?? 0 }}</td>
                                                                        </tr>
                                                                    @endfor
                                                                @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
    <script>
        // leggo le variabili CSS
        const style = getComputedStyle(document.documentElement);
        const primary = style.getPropertyValue('--primary-color').trim();
        const primaryXL = style.getPropertyValue('--primary-color-extralight').trim();
        const color3 = style.getPropertyValue('--color-3').trim();

        // — Fasce d'età (bar)
        new Chart("ageChart", {
            type: 'bar',
            data: {
                labels: @json($ageDistribution->keys()),
                datasets: [{
                    label: 'Utenti',               // ← qui
                    data: @json($ageDistribution->values()),
                    backgroundColor: color3,
                    borderColor: color3,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    }
                }
            }
        });

        // — Tempo dall'intervento (bar)
        new Chart("surgeryTimeChart", {
            type: 'bar',
            data: {
                labels: @json($surgeryTimeDist->keys()),
                datasets: [{
                    label: 'Numero utenti',        // ← e qui
                    data: @json($surgeryTimeDist->values()),
                    backgroundColor: primaryXL,
                    borderColor: primary,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    }
                }
            }
        });

        // — Tipo intervento (pie) e Mobilità iniziale (doughnut)
        new Chart("surgeryTypeChart", {
            type: 'pie',
            data: {
                labels: @json($surgeryTypeDist->keys()),
                datasets: [{
                    data: @json($surgeryTypeDist->values()),
                    backgroundColor: [primary, style.getPropertyValue('--primary-color-light').trim(), style.getPropertyValue('--secondary-color').trim()],
                    hoverOffset: 8
                }]
            },
            options: {responsive: true}
        });

        new Chart("movementChart", {
            type: 'doughnut',
            data: {
                labels: @json($movementDist->keys()),
                datasets: [{
                    data: @json($movementDist->values()),
                    backgroundColor: [primary, style.getPropertyValue('--secondary-color').trim(), style.getPropertyValue('--color-4').trim()],
                    hoverOffset: 6
                }]
            },
            options: {responsive: true}
        });
    </script>
@endpush


