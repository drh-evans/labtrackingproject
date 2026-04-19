@extends('layouts.labapp')
@section('title', 'Queue Analytics')

@section('content')
    <div class="max-w-8xl m-10 px-6 lg:px-8 mt-6 ">
        <div class="bg-gray-50 p-6  rounded-lg shadow-lg border border-gray-200">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10">

                <a href="{{ route('module.queue', $module) }}">
                    <x-tertiary-button>{{ __('return') }}</x-tertiary-button>
                </a>

                <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10">
                    <h1 class="text-2xl md:text-3xl font-semibold">
                        Queue Analytics for {{ $module->module_code }}, {{ $module->module_name }}
                    </h1>
                    <h2 class="text-md md:text-xl text-gray-700"> Module Coordinator:
                        @if ($module->leader)
                            {{ $module->leader->name }} ({{ $module->leader->email }})
                        @else
                            Staff member not found.
                        @endif
                    </h2>

                    <p class="mt-2 text-gray-600 max-w-2xl">
                        Stats aim to be as accurate as possible however real-time accuracy cannot be guaranteed. A page
                        refresh may be required to ensure full accuracy.
                    </p>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mx-10 mt-6">
            <div class="bg-gray-50 p-6  rounded-lg shadow-lg border border-gray-200 ">
                <h2 class="text-xs text-gray-700 uppercase"> Students Seen
                </h2>
                <p class="text-lg mt-2 text-gray-600 hover:text-black max-w-2xl font-semibold">
                    {{ $totalHelped }} Students </p>
            </div>

            <div class="bg-gray-50 p-6  rounded-lg shadow-lg border border-gray-200 ">
                <h2 class="text-xs text-gray-700 uppercase"> Current Queue Length
                </h2>
                <p class="text-lg mt-2 text-gray-600 hover:text-black max-w-2xl font-semibold">
                    {{ $currentLength }} Students </p>
            </div>

            <div class="bg-gray-50 p-6  rounded-lg shadow-lg border border-gray-200 ">
                <h2 class="text-xs text-gray-700 uppercase"> Average Wait Time
                </h2>
                <p class="text-lg mt-2 text-gray-600 hover:text-black max-w-2xl font-semibold">
                    {{ $averageWait }}
                </p>
            </div>

            <div class="bg-gray-50 p-6 rounded-lg shadow-lg border border-gray-200 ">
                <h2 class="text-xs text-gray-700 uppercase"> Highest Wait Time
                </h2>
                <p class="text-lg mt-2 text-gray-600 hover:text-black max-w-2xl font-semibold">
                    {{ $longestWait }}
                </p>
            </div>
        </div>

        <div class="bg-gray-50 p-6 mx-10 rounded-lg shadow-lg border border-gray-200 mt-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xs text-gray-700 uppercase"> Queue Length over time
                </h2>
                <a href="{{ route('queue.analytics', $module) }}">
                    <x-tertiary-button>{{ __('Refresh') }}</x-tertiary-button>
                </a>
            </div>
            <canvas id="queueLengthChart" height="80"></canvas>
        </div>

        <div class="bg-gray-50 p-6 mx-10 rounded-lg shadow-lg border border-gray-200 mt-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xs text-gray-700 uppercase"> Queue Length over time
                </h2>
                <a href="{{ route('queue.analytics', $module) }}">
                    <x-tertiary-button>{{ __('Refresh') }}</x-tertiary-button>
                </a>
            </div>
            <div class="max-w-sm mx-auto">
                <canvas id="assistanceTypeChart" height="80"></canvas>
            </div>
        </div>

        @if ($lengthOverTime)
            <!-- import chart.js-->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>

            <script>
                const ctx = document.getElementById('queueLengthChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($lengthOverTime->keys()),
                        datasets: [{
                            label: 'Queue Length',
                            data: @json($lengthOverTime->values()),
                            fill: true,
                            borderColor: 'rgb(28, 28, 132)',
                            backgroundColor: 'rgba(28, 28, 132,0.1)',
                            tension: 0.3,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });

                const ctx2 = document.getElementById('assistanceTypeChart').getContext('2d');
                new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: @json($assistanceTypeBreakdown->keys()),
                        datasets: [{
                            data: @json($assistanceTypeBreakdown->values()),
                            backgroundColor: ['rgba(253, 218, 13, 0.7)', 'rgba(9, 121, 105,0.7)'],
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true
                            }
                        }
                    }
                });
            </script>
        @endif
    @endsection
