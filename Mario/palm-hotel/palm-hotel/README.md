# The Palm Hotel — Website + Booking Backend

This package contains the hotel's website plus a PHP/MySQL backend:
a reservation form that saves to a database, email notifications,
and an admin panel to manage bookings and room types.

## What's inside

```
palm-hotel/
├── index.php              ← homepage (was index.html — now dynamic + booking form)
├── .htaccess
├── api/
│   └── book.php           ← receives the booking form (AJAX)
├── admin/                 ← admin panel (protect this, see below)
│   ├── setup.php           one-time: create your admin account
│   ├── login.php
│   ├── logout.php
│   ├── dashboard.php
│   ├── bookings.php        view / confirm / cancel / delete bookings
│   ├── rooms.php           list room types
│   ├── room-form.php       add / edit a room type
│   └── includes/
├── config/
│   ├── config.php          ← EDIT THIS: DB credentials, site email, secret key
│   └── db.php
├── includes/
│   └── functions.php
├── assets/css/admin.css
└── sql/
    └── schema.sql          ← import this into MySQL once
```

## 1. Create the MySQL database (Bluehost cPanel)

1. Log into cPanel → **MySQL Databases**.
2. Create a new database, e.g. `palmhotel` (Bluehost will prefix it,
   e.g. `yourcpaneluser_palmhotel`).
3. Create a new MySQL user with a strong password, and **add the user
   to the database** with **All Privileges**.
4. Go to cPanel → **phpMyAdmin**, select your new database, click the
   **Import** tab, choose `sql/schema.sql`, and click **Go**.
   This creates the `rooms`, `bookings`, and `admin_users` tables and
   pre-fills the 4 room types that were already on the site.

## 2. Configure the app

Open `config/config.php` and edit:

- `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` — from step 1
  (Bluehost host is normally `localhost`)
- `SITE_URL` — your real domain, e.g. `https://www.thepalmhotel.com`
- `RESERVATION_EMAIL` — where new-booking alerts are sent
  (defaults to `reservation@chain-luxe.com`, the address already on the site)
- `MAIL_FROM_EMAIL` — an address **on your own domain**
  (e.g. `no-reply@thepalmhotel.com`) — using an address on your own
  domain avoids emails being marked as spam
- `APP_SECRET` — replace with any long random string

## 3. Upload the files

Upload the entire contents of this folder to `public_html` (or a
subfolder, if the site lives in a subfolder) via cPanel **File
Manager** or FTP (e.g. FileZilla with the FTP details from Bluehost).

## 4. Create your admin account

Visit **`https://yourdomain.com/admin/setup.php`** once in your
browser. It will ask you to choose an admin username and password.
This page automatically refuses to run again once an account exists,
but for extra safety you can delete `admin/setup.php` afterwards.

Then log in at **`https://yourdomain.com/admin/login.php`**.

## 5. Test the booking form

Open the homepage, click **"Book This Room"** on any room or
**"Reserve Now"**, fill in the form, and submit. You should see:

- A new row in the `bookings` table (check via phpMyAdmin, or the
  admin panel's **Bookings** page)
- An email to `RESERVATION_EMAIL`
- A confirmation email to the guest's address

> **If emails don't arrive:** shared hosting's built-in `mail()`
> function usually works on Bluehost out of the box, but if
> deliverability is unreliable, swap `send_mail()` in
> `includes/functions.php` for **PHPMailer** configured with your
> Bluehost SMTP credentials (cPanel → Email Accounts → your address →
> "Connect Devices" shows the SMTP host/port). This is a drop-in
> change inside that one function.

## How availability works

Each room type has a `total_units` count (e.g. "32 Standard Rooms").
When someone submits a booking, the system counts how many
non-cancelled bookings already overlap those dates for that room
type; if it's already at `total_units`, the request is rejected with
a "fully booked" message. Adjust `total_units` per room type from
**Admin → Rooms → Edit**.

All new bookings start as **pending** — nothing is auto-confirmed.
Go to **Admin → Bookings** to mark a request **Confirmed** or
**Cancelled** once you've checked real availability and contacted the
guest.

## Managing rooms from the admin panel

**Admin → Rooms** lets you add, edit, or delete room types — name,
size, price, description, amenities, photos (paste an image URL —
you can host images on Bluehost under e.g. `/uploads/` and link to
`https://yourdomain.com/uploads/yourphoto.jpg`), and how many units
exist. Changes appear on the homepage immediately since the room
section is now generated from the database instead of being
hard-coded HTML.

## Security notes

- `config/`, `includes/`, and `sql/` each have a `.htaccess` that
  blocks direct browser access to those folders.
- Passwords are hashed with PHP's `password_hash()` (bcrypt) —
  never stored in plain text.
- The booking form and admin login both use CSRF tokens and basic
  rate-limiting.
- Change the default `APP_SECRET` in `config/config.php` before going live.
- Once everything works, set `APP_DEBUG` to `false` in
  `config/config.php` (it already defaults to `false`) so PHP errors
  are never shown to site visitors.

## Local testing (optional, before uploading)

If you have PHP installed locally, from inside the `palm-hotel`
folder run:

```
php -S localhost:8000
```

Note: you still need a MySQL server pointed at by `config/config.php`
for anything beyond viewing the page cosmetically — the room list,
the booking form, and the admin panel all require the database.
