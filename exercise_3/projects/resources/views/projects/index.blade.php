@extends('layouts.app')

@section('content')
<div class="space-y-12">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-slate-700/60">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">{{ __('Dashboard') }}</h1>
            <p class="text-slate-400 text-sm mt-1">{{ __('Manage your personal and collaborative projects') }}</p>
        </div>
        <a href="{{ route('projects.create') }}" class="bg-gradient-to-r from-teal-500 to-emerald-400 hover:from-teal-400 hover:to-emerald-300 text-slate-950 font-extrabold px-5 py-3 rounded-xl shadow-lg shadow-teal-500/20 hover:shadow-teal-500/30 transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>{{ __('New Project') }}</span>
        </a>
    </div>

    <!-- Section 1: Projects Managed by Me -->
    <div class="space-y-6">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-teal-500/10 border border-teal-500/20 text-teal-400 flex items-center justify-center text-sm shadow-sm">
                <i class="fa-solid fa-crown"></i>
            </div>
            <h2 class="text-xl font-bold text-white tracking-tight">{{ __('Projects Managed by Me') }}</h2>
            <span class="bg-teal-500/20 text-teal-300 text-xs font-bold px-2.5 py-0.5 rounded-full border border-teal-500/30">{{ $managed_projects -> count() }}</span>
        </div>

        @if ($managed_projects -> isEmpty())
            <div class="bg-slate-800/40 border border-dashed border-slate-700/60 rounded-2xl p-10 text-center">
                <i class="fa-solid fa-folder-open text-slate-600 text-4xl mb-3"></i>
                <p class="text-slate-400 font-medium">{{ __('No projects found in this section.') }}</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($managed_projects as $project)
                    <div class="bg-slate-800/80 backdrop-blur-md rounded-2xl border border-slate-700/60 p-6 flex flex-col justify-between shadow-xl hover:border-teal-500/30 transition-all group">
                        <div class="space-y-4">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-lg font-bold text-white group-hover:text-teal-400 transition-colors line-clamp-1">{{ $project -> name }}</h3>
                                <span class="bg-teal-500/10 text-teal-400 border border-teal-500/20 px-2.5 py-0.5 rounded-md text-xs font-bold shrink-0">
                                    ${{ number_format($project -> price, 2) }}
                                </span>
                            </div>

                            <p class="text-slate-400 text-sm line-clamp-2">{{ $project -> description }}</p>

                            <div class="bg-slate-900/60 rounded-xl p-3 border border-slate-700/40 space-y-2 text-xs">
                                <div class="flex items-center justify-between text-slate-400">
                                    <span><i class="fa-regular fa-calendar mr-1.5 text-teal-400"></i>{{ __('Start Date') }}:</span>
                                    <span class="font-semibold text-slate-200">{{ $project -> start_date }}</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-400">
                                    <span><i class="fa-regular fa-calendar-check mr-1.5 text-teal-400"></i>{{ __('End Date') }}:</span>
                                    <span class="font-semibold text-slate-200">{{ $project -> end_date }}</span>
                                </div>
                                <div class="pt-2 border-t border-slate-700/40">
                                    <span class="text-slate-400 block mb-1 font-semibold">{{ __('Done Jobs') }}:</span>
                                    <p class="text-slate-300 italic line-clamp-2">{{ $project -> done_jobs ?: '—' }}</p>
                                </div>
                            </div>

                            @if ($project -> members -> isNotEmpty())
                                <div>
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1.5">{{ __('Team Members') }}:</span>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach ($project -> members as $member)
                                            <span class="bg-slate-700/60 text-slate-300 text-xs px-2 py-0.5 rounded-md border border-slate-600/50">
                                                {{ $member -> name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="pt-5 mt-5 border-t border-slate-700/60">
                            <a href="{{ route('projects.edit', $project -> id) }}" class="w-full bg-slate-700/70 hover:bg-teal-500 hover:text-slate-950 text-white font-bold py-2 rounded-xl text-sm flex items-center justify-center gap-2 transition-all">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>{{ __('Edit') }}</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Section 2: Projects Where I Am a Team Member -->
    <div class="space-y-6 pt-6">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-sm shadow-sm">
                <i class="fa-solid fa-users"></i>
            </div>
            <h2 class="text-xl font-bold text-white tracking-tight">{{ __('Projects Where I Am a Team Member') }}</h2>
            <span class="bg-blue-500/20 text-blue-300 text-xs font-bold px-2.5 py-0.5 rounded-full border border-blue-500/30">{{ $member_projects -> count() }}</span>
        </div>

        @if ($member_projects -> isEmpty())
            <div class="bg-slate-800/40 border border-dashed border-slate-700/60 rounded-2xl p-10 text-center">
                <i class="fa-solid fa-user-group text-slate-600 text-4xl mb-3"></i>
                <p class="text-slate-400 font-medium">{{ __('No projects found in this section.') }}</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($member_projects as $project)
                    <div class="bg-slate-800/80 backdrop-blur-md rounded-2xl border border-slate-700/60 p-6 flex flex-col justify-between shadow-xl hover:border-blue-500/30 transition-all group">
                        <div class="space-y-4">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-lg font-bold text-white group-hover:text-blue-400 transition-colors line-clamp-1">{{ $project -> name }}</h3>
                                <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-2.5 py-0.5 rounded-md text-xs font-bold shrink-0">
                                    ${{ number_format($project -> price, 2) }}
                                </span>
                            </div>

                            <p class="text-slate-400 text-sm line-clamp-2">{{ $project -> description }}</p>

                            <div class="bg-slate-900/60 rounded-xl p-3 border border-slate-700/40 space-y-2 text-xs">
                                <div class="flex items-center justify-between text-slate-400">
                                    <span><i class="fa-solid fa-user-tie mr-1.5 text-blue-400"></i>{{ __('Manager') }}:</span>
                                    <span class="font-bold text-slate-200">{{ $project -> manager -> name }}</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-400">
                                    <span><i class="fa-regular fa-calendar mr-1.5 text-blue-400"></i>{{ __('Start Date') }}:</span>
                                    <span class="font-semibold text-slate-200">{{ $project -> start_date }}</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-400">
                                    <span><i class="fa-regular fa-calendar-check mr-1.5 text-blue-400"></i>{{ __('End Date') }}:</span>
                                    <span class="font-semibold text-slate-200">{{ $project -> end_date }}</span>
                                </div>
                                <div class="pt-2 border-t border-slate-700/40">
                                    <span class="text-slate-400 block mb-1 font-semibold">{{ __('Done Jobs') }}:</span>
                                    <p class="text-slate-300 italic line-clamp-2">{{ $project -> done_jobs ?: '—' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-5 mt-5 border-t border-slate-700/60">
                            <a href="{{ route('projects.edit', $project -> id) }}" class="w-full bg-slate-700/70 hover:bg-blue-500 hover:text-slate-950 text-white font-bold py-2 rounded-xl text-sm flex items-center justify-center gap-2 transition-all">
                                <i class="fa-solid fa-pen"></i>
                                <span>{{ __('Edit') }} ({{ __('Done Jobs') }})</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
