@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">{{ __('Create Project') }}</h1>
            <p class="text-slate-400 text-sm mt-1">{{ __('Fill out the details to launch a new project') }}</p>
        </div>
        <a href="{{ route('projects.index') }}" class="text-slate-400 hover:text-white text-sm font-semibold flex items-center gap-2 px-3 py-2 rounded-xl transition-colors bg-slate-800/80 border border-slate-700/60">
            <i class="fa-solid fa-arrow-left"></i>
            <span>{{ __('Back to Dashboard') }}</span>
        </a>
    </div>

    <div class="bg-slate-800/80 backdrop-blur-md rounded-3xl p-8 border border-slate-700/60 shadow-2xl">
        <form action="{{ route('projects.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">{{ __('Project Name') }}</label>
                <input type="text" name="name" id="name" required value="{{ old('name') }}" class="w-full bg-slate-900/80 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors">
            </div>

            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">{{ __('Description') }}</label>
                <textarea name="description" id="description" rows="3" required class="w-full bg-slate-900/80 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label for="price" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">{{ __('Price') }} ($)</label>
                    <input type="number" step="0.01" min="0" name="price" id="price" required value="{{ old('price') }}" class="w-full bg-slate-900/80 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors">
                </div>

                <div>
                    <label for="start_date" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">{{ __('Start Date') }}</label>
                    <input type="date" name="start_date" id="start_date" required value="{{ old('start_date') }}" class="w-full bg-slate-900/80 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors">
                </div>

                <div>
                    <label for="end_date" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">{{ __('End Date') }}</label>
                    <input type="date" name="end_date" id="end_date" required value="{{ old('end_date') }}" class="w-full bg-slate-900/80 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors">
                </div>
            </div>

            <div>
                <label for="done_jobs" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">{{ __('Done Jobs') }}</label>
                <textarea name="done_jobs" id="done_jobs" rows="2" placeholder="{{ __('Jobs completed so far') }}" class="w-full bg-slate-900/80 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors">{{ old('done_jobs') }}</textarea>
            </div>

            <!-- Team Members Selection -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-3">{{ __('Team Members') }} ({{ __('Select team members') }})</label>
                @if ($available_users -> isEmpty())
                    <p class="text-xs text-slate-500 italic">{{ __('No other registered users available yet.') }}</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-48 overflow-y-auto p-3 bg-slate-900/60 rounded-xl border border-slate-700/60">
                        @foreach ($available_users as $user)
                            <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-800/80 cursor-pointer transition-colors">
                                <input type="checkbox" name="members[]" value="{{ $user -> id }}" {{ in_array($user -> id, old('members', [])) ? 'checked' : '' }} class="w-4 h-4 rounded text-teal-500 focus:ring-teal-500 focus:ring-offset-slate-900 bg-slate-800 border-slate-600">
                                <div class="overflow-hidden">
                                    <span class="text-sm font-semibold text-white block truncate">{{ $user -> name }}</span>
                                    <span class="text-xs text-slate-400 block truncate">{{ $user -> email }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-teal-500 to-emerald-400 hover:from-teal-400 hover:to-emerald-300 text-slate-950 font-extrabold py-3.5 rounded-xl shadow-lg shadow-teal-500/20 hover:shadow-teal-500/30 transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-plus-circle"></i>
                <span>{{ __('Create Project') }}</span>
            </button>
        </form>
    </div>
</div>
@endsection
