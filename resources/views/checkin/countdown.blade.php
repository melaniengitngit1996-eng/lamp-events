@extends('layouts.checkin')

@section('style')
<style>
    #countdown {
        font-size: 2em;
        color: #333;
        margin-top: 50px;
    }
</style>
@endsection

@section('script')
<script>
    // Set the target date and time
    const targetDate = new Date("December 28, 2024 13:00:00 GMT+8:00").getTime();

    // Update the countdown every second
    const countdownInterval = setInterval(() => {
        const now = new Date().getTime();
        const timeLeft = targetDate - now;

        if (timeLeft < 0) {
            clearInterval(countdownInterval);
            document.getElementById("countdown").innerHTML = "Countdown Finished!";
            return;
        }

        // Calculate days, hours, minutes, and seconds
        const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
        const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

        // Display the countdown
        document.getElementById("countdown").innerHTML =
            `${days}d ${hours}h ${minutes}m ${seconds}s`;
    }, 1000);
</script>
@endsection

@section('content')
<div class="px-4 text-center">
<img height="300" width="300" class="mt-5" style="margin-top: 8%;" src="/images/choose.png" />
<h3 class="font-monospace mt-5 w-auto">Online check-in is still closed.</h3>
<p>Online check-in for members will open at <u>1:00 PM</u>. Kindly wait until then. Thank you for your patience.</p>

<div id="countdown"></div>
</div>
@endsection