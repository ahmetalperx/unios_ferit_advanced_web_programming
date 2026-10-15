@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-12">
    <div class="bg-slate-800/80 backdrop-blur-md rounded-3xl p-8 border border-slate-700/60 shadow-2xl">
        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-teal-500/10 border border-teal-500/20 text-teal-400 flex items-center justify-center text-2xl mx-auto mb-4 shadow-inner">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">{{ __('Login') }}</h1>
            <p class="text-slate-400 text-sm mt-2">{{ __('Sign in to your account') }}</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="flex flex-col gap-5">
            @csrf
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">{{ __('Email Address') }}</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}" class="w-full bg-slate-900/80 border border-slate-700 rounded-xl pl-11 pr-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">{{ __('Password') }}</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500">
                        <i class="fa-solid fa-key"></i>
                    </span>
                    <input type="password" name="password" id="password" required class="w-full bg-slate-900/80 border border-slate-700 rounded-xl pl-11 pr-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors">
                </div>
            </div>

            <button type="submit" class="mt-2 w-full bg-gradient-to-r from-teal-500 to-emerald-400 hover:from-teal-400 hover:to-emerald-300 text-slate-950 font-extrabold py-3.5 rounded-xl shadow-lg shadow-teal-500/20 hover:shadow-teal-500/30 transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
                <span>{{ __('Login') }}</span>
            </button>
        </form>

        <div class="text-center mt-6 pt-6 border-t border-slate-700/60">
            <p class="text-slate-400 text-sm">
                {{ __("Don't have an account?") }}
                <a href="{{ route('register') }}" class="text-teal-400 hover:text-teal-300 font-bold ml-1 transition-colors">{{ __('Register') }}</a>
            </p>
        </div>
    </div>
</div>
@endsection
