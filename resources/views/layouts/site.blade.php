<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preload" as="image" href="/images/site/lamp.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&amp;family=Inter:wght@300;400;500;600;700&amp;display=swap"
        data-precedence="default">
    <meta name="author" content="Lamp Church Events">
    <link href="{{ asset('css/site.css') }}" rel="stylesheet">
    <meta property="og:type" content="website">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <meta name="twitter:title" content="Lamp Church — Events & Gatherings">
    <meta name="twitter:description"
        content="Join Lamp Church for upcoming worship nights, festivals, and community gatherings.">
    <title>Lamp Church — Events & Gatherings</title>
    <meta name="description"
        content="Join Lamp Church for upcoming worship nights, festivals, and community gatherings. Register for our next event.">
    <meta property="og:title" content="Lamp Church — Events & Gatherings">
    <meta property="og:description"
        content="Join Lamp Church for upcoming worship nights, festivals, and community gatherings.">
    <style>
        .border-primary-foreground\/12 {
            border-color: color-mix(in oklab, var(--primary-foreground) 12%, transparent);
        }

        .border-primary-foreground\/15 {
            border-color:
                color-mix(in oklab, var(--primary-foreground) 15%, transparent);
        }

        .bg-gradient-gold {
            background-image: var(--gradient-gold);
        }

        .border-primary-foreground\/25 {
            border-color:
                color-mix(in oklab, var(--primary-foreground) 25%, transparent);
        }

        .border-primary-foreground\/10 {
            border-color:
                color-mix(in oklab, var(--primary-foreground) 10%, transparent);
        }

        .bg-midnight {
            background-color: oklch(18% .04 260);
        }

        .text-gold {
            color: oklch(78% .15 78);
        }
    </style>
</head>

<body>
    <div class="flex min-h-screen flex-col">
        <header class="sticky top-0 z-40 border-b border-border/60 bg-background/85 backdrop-blur-md">
            <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-5"><a
                    class="flex items-center gap-2.5 text-foreground active" href="/" data-status="active"
                    aria-current="page"><span
                        class="grid h-9 w-9 place-items-center rounded-full bg-midnight text-gold"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-flame h-4 w-4" aria-hidden="true">
                            <path
                                d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4">
                            </path>
                        </svg></span><span class="font-display text-xl font-semibold tracking-tight">Lamp
                        Church Events</span></a>
                {{-- <nav class="hidden items-center gap-7 md:flex"><a
                        class="text-sm font-medium text-muted-foreground transition-colors hover:text-foreground text-foreground"
                        href="/" data-status="active" aria-current="page">Home</a><a href="/events"
                        class="text-sm font-medium text-muted-foreground transition-colors hover:text-foreground">Events</a><a
                        href="/past"
                        class="text-sm font-medium text-muted-foreground transition-colors hover:text-foreground">Past
                        Events</a><a href="/about"
                        class="text-sm font-medium text-muted-foreground transition-colors hover:text-foreground">Our
                        Story</a></nav> --}}
                <div class="hidden items-center gap-3 md:flex"><a href="/login"
                        class="text-sm font-medium text-muted-foreground hover:text-foreground">Admin login</a></div>
                <button class="md:hidden" aria-label="Toggle menu"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu h-5 w-5"
                        aria-hidden="true">
                        <path d="M4 5h16"></path>
                        <path d="M4 12h16"></path>
                        <path d="M4 19h16"></path>
                    </svg></button>
            </div>
        </header>
        <main class="flex-1">
            @yield('content')
        </main>
        {{-- border-t --}}
        <footer class=" border-border/60 bg-midnight text-primary-foreground">
            <div class="mx-auto grid max-w-6xl gap-8 px-5 py-12 md:grid-cols-3">
                <div>
                    <div class="flex items-center gap-2.5"><span
                            class="grid h-9 w-9 place-items-center rounded-full bg-gold text-midnight"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-flame h-4 w-4" aria-hidden="true">
                                <path
                                    d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4">
                                </path>
                            </svg></span><span class="font-display text-xl font-semibold">Lamp Church</span></div>
                    <p class="mt-3 max-w-xs text-sm text-primary-foreground/65">For 39 years, we've worshiped, grown, and served together—guided by God's faithfulness and love.</p>
                </div>
                <div class="text-sm">
                    <h4 class="font-display text-base text-gold">Visit</h4>
                    <p class="mt-2 text-primary-foreground/70">Park Homes Subdivision, Midland Alley, Tunasan, Muntinlupa, Metro Manila<br>Sundays · 9am</p>
                </div>
                <div class="text-sm">
                    <h4 class="font-display text-base text-gold">Contact</h4>
                    <p class="mt-2 text-primary-foreground/70">join@lampchurchevents.com{{-- <br>+1 (555) 412-8800 --}}</p>
                </div>
            </div>
            <div class="border-t border-primary-foreground/10 py-4 text-center text-xs text-primary-foreground/50">©
                <!-- -->2026<!-- --> Lamp Church
            </div>
        </footer>
    </div>
    @yield('scripts')
</body>

</html>