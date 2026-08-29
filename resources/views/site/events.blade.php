@extends('layouts.site')

@section('content')
<main class="flex-1">
   <!--$--><!--/$-->
   <div class="mx-auto max-w-6xl px-5 py-16 md:py-24">
      <div class="max-w-2xl">
         <p class="text-xs font-semibold uppercase tracking-[0.3em] text-gold">What's coming</p>
         <h1 class="mt-4 font-display text-5xl font-medium tracking-tight">Upcoming events</h1>
         <p class="mt-4 text-muted-foreground">Everyone is welcome. Register so we know to save you a seat.</p>
      </div>
      <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
         <article class="flex flex-col rounded-2xl border border-border bg-card p-6 shadow-warm/40 transition-shadow hover:shadow-warm">
            <h2 class="font-display text-2xl font-medium leading-tight">39th LAMP Church Anniversary Celebration</h2>
            <div class="mt-3 space-y-1.5 text-sm text-muted-foreground">
               <p class="inline-flex items-center gap-1.5">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-days h-4 w-4 text-gold" aria-hidden="true">
                     <path d="M8 2v4"></path>
                     <path d="M16 2v4"></path>
                     <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                     <path d="M3 10h18"></path>
                     <path d="M8 14h.01"></path>
                     <path d="M12 14h.01"></path>
                     <path d="M16 14h.01"></path>
                     <path d="M8 18h.01"></path>
                     <path d="M12 18h.01"></path>
                     <path d="M16 18h.01"></path>
                  </svg>
                  Sunday 5 July 2026 at 09:00
               </p>
               <p class="inline-flex items-center gap-1.5">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-4 w-4 text-gold" aria-hidden="true">
                     <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                     <circle cx="12" cy="10" r="3"></circle>
                  </svg>
                  Park Homes Subdivision, Midland Alley, Tunasan, Muntinlupa, Metro Manila
               </p>
            </div>
            {{-- <p class="mt-4 flex-1 text-sm text-foreground/80 line-clamp-4">An evening of worship, testimonies, and community as we celebrate the light of Christ together. Join hundreds of believers for an unforgettable night.</p> --}}
            <div class="mt-5">
               <a href="#" class="inline-flex items-center rounded-md bg-midnight px-4 py-2 text-sm font-medium text-gold transition-opacity hover:opacity-90 disabled-link" type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="radix-_r_1d_" data-state="closed">Register</a>
            </div>
         </article>
         <article class="flex flex-col rounded-2xl border border-border bg-card p-6 shadow-warm/40 transition-shadow hover:shadow-warm">
            <h2 class="font-display text-2xl font-medium leading-tight">Annual Worship & Thanksgiving Assembly 2026</h2>
            <div class="mt-3 space-y-1.5 text-sm text-muted-foreground">
               <p class="inline-flex items-center gap-1.5">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-days h-4 w-4 text-gold" aria-hidden="true">
                     <path d="M8 2v4"></path>
                     <path d="M16 2v4"></path>
                     <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                     <path d="M3 10h18"></path>
                     <path d="M8 14h.01"></path>
                     <path d="M12 14h.01"></path>
                     <path d="M16 14h.01"></path>
                     <path d="M8 18h.01"></path>
                     <path d="M12 18h.01"></path>
                     <path d="M16 18h.01"></path>
                  </svg>
                  December 26 to 29, 2026
               </p>
               <p class="inline-flex items-center gap-1.5">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-4 w-4 text-gold" aria-hidden="true">
                     <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                     <circle cx="12" cy="10" r="3"></circle>
                  </svg>
                  Calamba Tent, CMC Ave, Real, Calamba, 4027 Laguna
               </p>
            </div>
            {{-- <p class="mt-4 flex-1 text-sm text-foreground/80 line-clamp-4">An evening of worship, testimonies, and community as we celebrate the light of Christ together. Join hundreds of believers for an unforgettable night.</p> --}}
            <div class="mt-5">
               <a href="/registration" class="inline-flex items-center rounded-md bg-midnight px-4 py-2 text-sm font-medium text-gold transition-opacity hover:opacity-90" type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="radix-_r_1d_" data-state="closed">Register</a>
            </div>
         </article>
      </div>
   </div>
</main>
@endsection