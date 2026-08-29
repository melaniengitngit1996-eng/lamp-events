@extends('layouts.site')

@section('content')
<div class="mx-auto max-w-4xl px-5 py-16 md:py-24">
   <p class="text-xs font-semibold uppercase tracking-[0.3em] text-gold">A look back</p>
   <h1 class="mt-4 font-display text-5xl font-medium tracking-tight">Past events</h1>
   <p class="mt-4 max-w-2xl text-muted-foreground">The moments we've shared. Each one carried by the people who showed up.</p>
   <ol class="mt-14 relative border-l border-border pl-8">
      <li class="relative mb-12 last:mb-0">
         <span class="absolute -left-[37px] top-2 grid h-4 w-4 place-items-center rounded-full bg-gradient-gold shadow-glow"></span>
         <p class="text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">5 July 2026</p>
         <h2 class="mt-1.5 font-display text-2xl font-medium">39th LAMP Church Anniversary Celebration</h2>
         <p class="mt-1 inline-flex items-center gap-1.5 text-sm text-muted-foreground">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-3.5 w-3.5 text-gold" aria-hidden="true">
               <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
               <circle cx="12" cy="10" r="3"></circle>
            </svg>
            Park Homes Subdivision, Midland Alley, Tunasan, Muntinlupa, Metro Manila
         </p>
         <p class="mt-3 text-foreground/80">Through every season, God's hand has guided us, His love has sustained us, and His promises have remained true. Join us as we give thanks for 39 years of grace, growth, and purpose—and look ahead to the lives still waiting to be reached.</p>
      </li>
   </ol>
</div>
@endsection