#! /bin/bash

git pull origin version/2025.1

nvm install 16.15.1
npm run prod
# no need to compile because the compiled js files are commitable

rm public_html/js/app.js
rm public_html/js/activities.js
rm public_html/js/registration.js
rm public_html/js/payment.js
rm public_html/js/attendance.js
rm public_html/js/booking.js
rm public_html/js/dashboard.js
rm public_html/js/checkin.js

cp public/js/app.js public_html/js
cp public/js/attendance.js public_html/js
cp public/js/registration.js public_html/js
cp public/js/payment.js public_html/js
cp public/js/activities.js public_html/js
cp public/js/booking.js public_html/js
cp public/js/dashboard.js public_html/js
cp public/js/checkin.js public_html/js

php artisan config:clear

php artisan cache:clear

php artisan config:cache
