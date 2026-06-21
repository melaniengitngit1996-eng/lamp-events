@extends('layouts.site')

@section('content')
<div class="mx-auto max-w-4xl px-5 py-16 md:py-24">
   <p class="text-xs font-semibold uppercase tracking-[0.3em] text-gold">A look back</p>
   <h1 class="mt-4 font-display text-5xl font-medium tracking-tight">Past events</h1>
   <p class="mt-4 max-w-2xl text-muted-foreground">The moments we've shared. Each one carried by the people who showed up.</p>
   <ol class="mt-14 relative border-l border-border pl-8">
      <li class="relative mb-12 last:mb-0">
         <span class="absolute -left-[37px] top-2 grid h-4 w-4 place-items-center rounded-full bg-gradient-gold shadow-glow"></span>
         <p class="text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">15 June 2026</p>
         <h2 class="mt-1.5 font-display text-2xl font-medium">Festival of Light 2026</h2>
         <p class="mt-1 inline-flex items-center gap-1.5 text-sm text-muted-foreground">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-3.5 w-3.5 text-gold" aria-hidden="true">
               <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
               <circle cx="12" cy="10" r="3"></circle>
            </svg>
            Lamp Church Main Sanctuary, 142 Grace Avenue
         </p>
         <p class="mt-3 text-foreground/80">An evening of worship, testimonies, and community as we celebrate the light of Christ together. Join hundreds of believers for an unforgettable night.</p>
      </li>
      <li class="relative mb-12 last:mb-0">
         <span class="absolute -left-[37px] top-2 grid h-4 w-4 place-items-center rounded-full bg-gradient-gold shadow-glow"></span>
         <p class="text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">2 April 2026</p>
         <h2 class="mt-1.5 font-display text-2xl font-medium">Summer Youth Camp</h2>
         <p class="mt-1 inline-flex items-center gap-1.5 text-sm text-muted-foreground">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-3.5 w-3.5 text-gold" aria-hidden="true">
               <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
               <circle cx="12" cy="10" r="3"></circle>
            </svg>
            Cedar Ridge Retreat Center
         </p>
         <p class="mt-3 text-foreground/80">Three days of teaching, games, and bonfires for ages 13–18 in the hills outside the city.</p>
      </li>
      <li class="relative mb-12 last:mb-0">
         <span class="absolute -left-[37px] top-2 grid h-4 w-4 place-items-center rounded-full bg-gradient-gold shadow-glow"></span>
         <p class="text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">3 December 2025</p>
         <h2 class="mt-1.5 font-display text-2xl font-medium">Christmas Eve Candlelight Service</h2>
         <p class="mt-1 inline-flex items-center gap-1.5 text-sm text-muted-foreground">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-3.5 w-3.5 text-gold" aria-hidden="true">
               <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
               <circle cx="12" cy="10" r="3"></circle>
            </svg>
            Lamp Church Main Sanctuary
         </p>
         <p class="mt-3 text-foreground/80">Our annual candlelight service welcoming the season with hymns, scripture, and community.</p>
      </li>
      <li class="relative mb-12 last:mb-0">
         <span class="absolute -left-[37px] top-2 grid h-4 w-4 place-items-center rounded-full bg-gradient-gold shadow-glow"></span>
         <p class="text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">5 August 2025</p>
         <h2 class="mt-1.5 font-display text-2xl font-medium">Easter Sunrise Gathering</h2>
         <p class="mt-1 inline-flex items-center gap-1.5 text-sm text-muted-foreground">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-3.5 w-3.5 text-gold" aria-hidden="true">
               <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
               <circle cx="12" cy="10" r="3"></circle>
            </svg>
            Hillcrest Park, East Lawn
         </p>
         <p class="mt-3 text-foreground/80">Greeting Resurrection morning together at the lakeside.</p>
      </li>
   </ol>
</div>
@endsection