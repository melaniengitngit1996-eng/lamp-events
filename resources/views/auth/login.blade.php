@extends('layouts.site')

@section('content')
<div class="mx-auto flex max-w-md flex-col items-center px-5 py-20">
<span class="grid h-12 w-12 place-items-center rounded-full bg-midnight text-gold">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flame h-5 w-5" aria-hidden="true">
        <path d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4"></path>
    </svg>
</span>
<h1 class="mt-5 font-display text-3xl font-medium">Admin access</h1>
<p class="mt-2 text-center text-sm text-muted-foreground">Sign in to manage events and view registrations.</p>
<form class="mt-8 w-full space-y-4 rounded-2xl border border-border bg-card p-6" method="POST" action="{{ route('login') }}">
    @csrf
    <div class="space-y-1.5">
        <label class="text-sm font-medium" for="email">Email</label>
        <input id="email" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-ring form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
        @error('email')
            <span class="invalid-feedback text-destructive text-xs" role="alert">
                {{ $message }}
            </span>
        @enderror
    </div>
    <div class="space-y-1.5">
        <label class="text-sm font-medium" for="password">Password</label>
        <input id="password" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-ring form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password">
        @error('password')
            <span class="invalid-feedback text-destructive text-xs" role="alert">
                {{ $message }}
            </span>
        @enderror
    </div>
    <button type="submit" class="w-full rounded-md bg-midnight px-4 py-2.5 text-sm font-medium text-gold transition-opacity hover:opacity-90 disabled:opacity-50">Sign in</button>
    {{-- <button type="button" class="block w-full text-center text-xs text-muted-foreground hover:text-foreground">Need an account? Sign up</button> --}}
</form>
</div>
@endsection