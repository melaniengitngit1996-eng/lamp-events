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
        <main class="flex-1"><!--$--><!--/$-->
            <section class="relative isolate overflow-hidden bg-midnight text-primary-foreground">
                <img alt="Lamp Church sanctuary lit by candlelight" width="1920" height="1280"
                    class="absolute inset-0 h-full w-full object-cover opacity-55" src="/images/site/lamp.png">
                <div class="absolute inset-0 bg-gradient-hero"></div>
                <div class="relative mx-auto max-w-6xl px-5 py-24 sm:py-32 md:py-40">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-gold">Lamp Church · Est. 1987</p>
                    <h1
                        class="mt-5 max-w-3xl font-display text-5xl font-medium leading-[1.05] tracking-tight sm:text-6xl md:text-7xl">
                        Celebrating 39 Years of God's Faithfulness</h1>
                    <p class="mt-6 max-w-xl text-lg text-primary-foreground/75">Through every season, God's hand has guided us, His love has sustained us, and His promises have remained true. Join us as we give thanks for 39 years of grace, growth, and purpose—and look ahead to the lives still waiting to be reached.</p>
                    <div
                        class="mt-12 max-w-3xl rounded-2xl border border-primary-foreground/12 bg-primary-foreground/[0.04] p-6 backdrop-blur-md sm:p-8">
                        <div
                            class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.22em] text-gold">
                            <span class="inline-block h-1.5 w-1.5 animate-pulse rounded-full bg-gold"></span>Next event
                        </div>
                        <h2 class="mt-3 font-display text-3xl font-medium sm:text-4xl">39th LAMP Church Anniversary Celebration</h2>
                        <div class="mt-3 flex flex-wrap gap-x-5 gap-y-1.5 text-sm text-primary-foreground/75"><span
                                class="inline-flex items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-calendar-days h-4 w-4 text-gold" aria-hidden="true">
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
                                </svg>Sunday 5 July 2026 at 09:00</span><span
                                class="inline-flex items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-map-pin h-4 w-4 text-gold" aria-hidden="true">
                                    <path
                                        d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0">
                                    </path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>Park Homes Subdivision, Midland Alley, Tunasan, Muntinlupa, Metro Manila</span></div>
                        <div class="mt-6">
                            <div class="flex flex-wrap gap-3 sm:gap-4">
                                <div
                                    class="min-w-[78px] flex-1 rounded-lg border px-3 py-4 text-center sm:min-w-[96px] border-primary-foreground/15 bg-primary-foreground/5 backdrop-blur">
                                    <div id="countdown-days"
                                        class="font-display text-3xl font-semibold tabular-nums sm:text-4xl text-gold">
                                        00
                                    </div>
                                    <div
                                        class="mt-1 text-[10px] font-medium uppercase tracking-[0.18em] sm:text-xs text-primary-foreground/60">
                                        Days
                                    </div>
                                </div>

                                <div
                                    class="min-w-[78px] flex-1 rounded-lg border px-3 py-4 text-center sm:min-w-[96px] border-primary-foreground/15 bg-primary-foreground/5 backdrop-blur">
                                    <div id="countdown-hours"
                                        class="font-display text-3xl font-semibold tabular-nums sm:text-4xl text-gold">
                                        00
                                    </div>
                                    <div
                                        class="mt-1 text-[10px] font-medium uppercase tracking-[0.18em] sm:text-xs text-primary-foreground/60">
                                        Hours
                                    </div>
                                </div>

                                <div
                                    class="min-w-[78px] flex-1 rounded-lg border px-3 py-4 text-center sm:min-w-[96px] border-primary-foreground/15 bg-primary-foreground/5 backdrop-blur">
                                    <div id="countdown-minutes"
                                        class="font-display text-3xl font-semibold tabular-nums sm:text-4xl text-gold">
                                        00
                                    </div>
                                    <div
                                        class="mt-1 text-[10px] font-medium uppercase tracking-[0.18em] sm:text-xs text-primary-foreground/60">
                                        Minutes
                                    </div>
                                </div>

                                <div
                                    class="min-w-[78px] flex-1 rounded-lg border px-3 py-4 text-center sm:min-w-[96px] border-primary-foreground/15 bg-primary-foreground/5 backdrop-blur">
                                    <div id="countdown-seconds"
                                        class="font-display text-3xl font-semibold tabular-nums sm:text-4xl text-gold">
                                        00
                                    </div>
                                    <div
                                        class="mt-1 text-[10px] font-medium uppercase tracking-[0.18em] sm:text-xs text-primary-foreground/60">
                                        Seconds
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-7 flex flex-wrap gap-3">
                            <a href="/registration" class="inline-flex items-center gap-2 rounded-md bg-gradient-gold px-6 py-3 text-sm font-semibold text-midnight shadow-glow transition-transform hover:scale-[1.02]">
                                Register now
                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-arrow-right h-4 w-4" aria-hidden="true">
                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>
                                </svg>
                            </a>
                            {{-- <a href="/events" class="inline-flex items-center rounded-md border border-primary-foreground/25 px-6 py-3 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary-foreground/5">
                                All events
                            </a> --}}
                        </div>
                    </div>
                </div>
            </section>
            {{-- <section class="mx-auto grid max-w-6xl gap-12 px-5 py-20 md:grid-cols-2 md:items-center md:py-28">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-gold">Our story</p>
                    <h2 class="mt-4 font-display text-4xl font-medium tracking-tight sm:text-5xl">Started in a living
                        room. Still gathering today.</h2>
                    <p class="mt-5 text-muted-foreground">Lamp Church began in 1987 with twelve neighbors meeting around
                        a single oil lamp on Marsden Street. Half a century later, that lamp still sits on our altar — a
                        reminder that even a small light, tended faithfully, can reach a whole community.</p><a
                        href="/about"
                        class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-foreground">Read our full
                        story <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-arrow-right h-4 w-4" aria-hidden="true">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg></a>
                </div>
                <div class="relative"><img alt="Brass oil lamp" loading="lazy" width="1024" height="1024"
                        class="aspect-square w-full rounded-2xl object-cover shadow-warm" src="/images/site/lamp.jpg"></div>
            </section> --}}
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script>
        $(function () {

            // July 5, 2026 9:00 AM (Philippines Time)
            const targetDate = new Date('2026-07-05T09:00:00+08:00').getTime();

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = targetDate - now;

                if (distance <= 0) {
                    $('#countdown-days').text('00');
                    $('#countdown-hours').text('00');
                    $('#countdown-minutes').text('00');
                    $('#countdown-seconds').text('00');
                    clearInterval(timer);
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor(
                    (distance % (1000 * 60 * 60 * 24)) /
                    (1000 * 60 * 60)
                );
                const minutes = Math.floor(
                    (distance % (1000 * 60 * 60)) /
                    (1000 * 60)
                );
                const seconds = Math.floor(
                    (distance % (1000 * 60)) /
                    1000
                );

                $('#countdown-days').text(days);
                $('#countdown-hours').text(String(hours).padStart(2, '0'));
                $('#countdown-minutes').text(String(minutes).padStart(2, '0'));
                $('#countdown-seconds').text(String(seconds).padStart(2, '0'));
            }

            updateCountdown();
            const timer = setInterval(updateCountdown, 1000);

        });
    </script>
</body>

</html>