@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">{{ __('Edit') }}: {{ $project -> name }}</h1>
            <div class="flex items-center gap-2 mt-1">
                @if ($is_manager)
                    <span class="bg-teal-500/20 text-teal-300 border border-teal-500/30 text-xs font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1.5">
                        <i class="fa-solid fa-crown text-xs"></i>
                        <span>{{ __('Manager (Full Access)') }}</span>
                    </span>
                @else
                    <span class="bg-blue-500/20 text-blue-300 border border-blue-500/30 text-xs font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1.5">
                        <i class="fa-solid fa-user-group text-xs"></i>
                        <span>{{ __('Team Member (Restricted Access)') }}</span>
                    </span>
                @endif
            </div>
        </div>
        <a href="{{ route('projects.index') }}" class="text-slate-400 hover:text-white text-sm font-semibold flex items-center gap-2 px-3 py-2 rounded-xl transition-colors bg-slate-800/80 border border-slate-700/60">
            <i class="fa-solid fa-arrow-left"></i>
            <span>{{ __('Back to Dashboard') }}</span>
        </a>
    </div>

    @if (!$is_manager)
        <div class="mb-6 p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-300 flex items-center gap-3 text-sm font-medium shadow-md">
            <i class="fa-solid fa-circle-info text-lg shrink-0"></i>
            <span>{{ __('Only manager can edit project details. As a team member, you can only update completed jobs.') }}</span>
        </div>
    @endif

    <div class="bg-slate-800/80 backdrop-blur-md rounded-3xl p-8 border border-slate-700/60 shadow-2xl">
        <form action="{{ route('projects.update', $project -> id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">{{ __('Project Name') }}</label>
                <input type="text" name="name" id="name" {{ $is_manager ? 'required' : 'disabled' }} value="{{ old('name', $project -> name) }}" class="w-full bg-slate-900/80 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors {{ !$is_manager ? 'opacity-60 cursor-not-allowed bg-slate-950' : '' }}">
            </div>

            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">{{ __('Description') }}</label>
                <textarea name="description" id="description" rows="3" {{ $is_manager ? 'required' : 'disabled' }} class="w-full bg-slate-900/80 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors {{ !$is_manager ? 'opacity-60 cursor-not-allowed bg-slate-950' : '' }}">{{ old('description', $project -> description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label for="price" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">{{ __('Price') }} ($)</label>
                    <input type="number" step="0.01" min="0" name="price" id="price" {{ $is_manager ? 'required' : 'disabled' }} value="{{ old('price', $project -> price) }}" class="w-full bg-slate-900/80 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors {{ !$is_manager ? 'opacity-60 cursor-not-allowed bg-slate-950' : '' }}">
                </div>

                <div>
                    <label for="start_date" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">{{ __('Start Date') }}</label>
                    <input type="date" name="start_date" id="start_date" {{ $is_manager ? 'required' : 'disabled' }} value="{{ old('start_date', $project -> start_date) }}" class="w-full bg-slate-900/80 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors {{ !$is_manager ? 'opacity-60 cursor-not-allowed bg-slate-950' : '' }}">
                </div>

                <div>
                    <label for="end_date" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">{{ __('End Date') }}</label>
                    <input type="date" name="end_date" id="end_date" {{ $is_manager ? 'required' : 'disabled' }} value="{{ old('end_date', $project -> end_date) }}" class="w-full bg-slate-900/80 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors {{ !$is_manager ? 'opacity-60 cursor-not-allowed bg-slate-950' : '' }}">
                </div>
            </div>

            <!-- Done Jobs (Always editable by both Manager and Team Member!) -->
            <div class="p-4 rounded-2xl bg-teal-500/5 border border-teal-500/20">
                <div class="flex items-center justify-between mb-2">
                    <label for="done_jobs" class="block text-xs font-bold uppercase tracking-wider text-teal-400">{{ __('Done Jobs') }}</label>
                    <span class="text-xs text-teal-300/80 font-semibold">{{ __('Editable by all members') }}</span>
                </div>
                <textarea name="done_jobs" id="done_jobs" rows="3" placeholder="{{ __('Jobs completed so far') }}" class="w-full bg-slate-900 border border-teal-500/40 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-400 transition-colors">{{ old('done_jobs', $project -> done_jobs) }}</textarea>
            </div>

            @if ($is_manager)
                <!-- Team Members Selection (Only for Manager) -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-3">{{ __('Team Members') }} ({{ __('Select team members') }})</label>
                    @if ($available_users -> isEmpty())
                        <p class="text-xs text-slate-500 italic">{{ __('No other registered users available yet.') }}</p>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-48 overflow-y-auto p-3 bg-slate-900/60 rounded-xl border border-slate-700/60">
                            @foreach ($available_users as $user)
                                <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-800/80 cursor-pointer transition-colors">
                                    <input type="checkbox" name="members[]" value="{{ $user -> id }}" {{ $project -> members -> contains($user -> id) ? 'checked' : '' }} class="w-4 h-4 rounded text-teal-500 focus:ring-teal-500 focus:ring-offset-slate-900 bg-slate-800 border-slate-600">
                                    <div class="overflow-hidden">
                                        <span class="text-sm font-semibold text-white block truncate">{{ $user -> name }}</span>
                                        <span class="text-xs text-slate-400 block truncate">{{ $user -> email }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            <button type="submit" class="w-full bg-gradient-to-r from-teal-500 to-emerald-400 hover:from-teal-400 hover:to-emerald-300 text-slate-950 font-extrabold py-3.5 rounded-xl shadow-lg shadow-teal-500/20 hover:shadow-teal-500/30 transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>{{ __('Save Changes') }}</span>
            </button>
        </form>
    </div>
</div>
@endsection
