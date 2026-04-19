@extends('layouts.labapp')
@section('title', 'Chatroom Analytics')

@section('content')
    <div class="max-w-8xl m-10 px-6 lg:px-8 mt-6 ">
        <div class="bg-gray-50 p-6  rounded-lg shadow-lg border border-gray-200">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10">

                <a href="{{ route('chatroom.show', $module) }}">
                    <x-tertiary-button>{{ __('return') }}</x-tertiary-button>
                </a>

                <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10">
                    <h1 class="text-2xl md:text-3xl font-semibold">
                        Chatroom Analytics for {{ $module->module_code }}, {{ $module->module_name }}
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
                <h2 class="text-xs text-gray-700 uppercase"> Module Members
                </h2>
                <p class="text-lg mt-2 text-gray-600 hover:text-black max-w-2xl font-semibold">
                    {{ $totalModuleMembers }} Users </->
            </div>

            <div class="bg-gray-50 p-6  rounded-lg shadow-lg border border-gray-200 ">
                <h2 class="text-xs text-gray-700 uppercase"> Unique Participants
                </h2>
                <p class="text-lg mt-2 text-gray-600 hover:text-black max-w-2xl font-semibold">
                    {{ $totalUniqueSenders }} Users </p>
            </div>

            <div class="bg-gray-50 p-6  rounded-lg shadow-lg border border-gray-200 ">
                <h2 class="text-xs text-gray-700 uppercase"> Percentage Participation
                </h2>
                <p class="text-lg mt-2 text-gray-600 hover:text-black max-w-2xl font-semibold">
                    {{ $percentageParticipation }}%
                </p>
            </div>

            <div class="bg-gray-50 p-6 rounded-lg shadow-lg border border-gray-200 ">
                <h2 class="text-xs text-gray-700 uppercase"> Total Messages
                </h2>
                <a href="{{ route('chatroom.show', $module) }}"
                    class="text-lg mt-2 text-gray-600 hover:text-black max-w-2xl font-semibold">
                    {{ $totalMessages }} Messages
                </a>
            </div>
        </div>

        <div class="bg-gray-50 p-6 mx-10 rounded-lg shadow-lg border border-gray-200 mt-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xs text-gray-700 uppercase"> Messages Received
                </h2>
                <a href="{{ route('chatroom.analytics', $module) }}">
                    <x-tertiary-button>{{ __('Refresh') }}</x-tertiary-button>
                </a>
            </div>
            <canvas id="messageFrequencyChart" height="80"></canvas>
        </div>

        <div class="bg-gray-50 p-6 mx-10 rounded-lg shadow-lg border border-gray-200 mt-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xs text-gray-700 uppercase"> Common Words Mentioned
                </h2>
                <a href="{{ route('chatroom.analytics', $module) }}">
                    <x-tertiary-button>{{ __('Refresh') }}</x-tertiary-button>
                </a>
            </div>
            <div class="flex items-center justify-center">
                <canvas id="wordCloud" width="800" height="400"></canvas>

            </div>
        </div>

        @if ($totalMessages)
            <!-- import char.js and wordcloud2.js-->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/wordcloud2.js/1.0.1/wordcloud2.min.js"></script>

            <script>
                const ctx = document.getElementById('messageFrequencyChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($messagesPer10Mins->keys()),
                        datasets: [{
                            label: 'Messages',
                            data: @json($messagesPer10Mins->values()),
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
                })
            </script>
            @if (!empty($wordFrequency))
                <script>
                    const wordData = @json(collect($wordFrequency)->map(fn($count, $word) => [$word, $count])->values());
                    WordCloud(document.getElementById('wordCloud'), {
                        list: wordData,
                        gridSize: 10,
                        rotateRatio: 0.2,
                        weightFactor: 20,
                        fontFamily: 'sans-serif',
                        color: 'random-dark',
                        backgroundColor: 'transparent',
                    });
                </script>
            @endif
        @endif
    @endsection
