<script>
  import Header from './Views/components/Header.svelte';
  import Hero from './Views/components/Hero.svelte';
  import Overview from './Views/components/Overview.svelte';
  import Location from './Views/components/Location.svelte';
  import Reception from './Views/components/Reception.svelte';
  import Rooms from './Views/components/Rooms.svelte';
  import Dining from './Views/components/Dining.svelte';
  import Events from './Views/components/Events.svelte';
  import Kids from './Views/components/Kids.svelte';
  import Amenities from './Views/components/Amenities.svelte';
  import Offers from './Views/components/Offers.svelte';
  import Guests from './Views/components/Guests.svelte';
  import Policies from './Views/components/Policies.svelte';
  import CTA from './Views/components/CTA.svelte';
  import Footer from './Views/components/Footer.svelte';
  import BookingModal from './Views/components/BookingModal.svelte';

  let bookingOpen = false;
  let bookingRoomType = '';
  let bookingGuests = '';

  function openBookingModal(event) {
    if (event && event.detail) {
      bookingRoomType = event.detail.roomType || '';
      bookingGuests = event.detail.guests || '';
    } else {
      bookingRoomType = '';
      bookingGuests = '';
    }
    bookingOpen = true;
  }

  function closeBookingModal() {
    bookingOpen = false;
    bookingRoomType = '';
    bookingGuests = '';
  }
</script>

<svelte:head>
  <title>The Palm Hotel | Luxury Stay in Kafr El Sheikh</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="The Palm Hotel offers luxury rooms, dining, events, and premium hospitality experiences in Kafr El Sheikh." />
  <meta name="keywords" content="The Palm Hotel, luxury hotel, Kafr El Sheikh hotel, hotel rooms, spa, dining, events, reservations" />
  <meta name="author" content="The Palm Hotel" />
  <meta name="robots" content="index, follow" />
  <meta property="og:title" content="The Palm Hotel | Luxury Stay in Kafr El Sheikh" />
  <meta property="og:description" content="Discover luxury rooms, elegant dining, and unforgettable experiences at The Palm Hotel." />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://the-palm.vercel.app/" />
  <meta property="og:image" content="https://the-palm.vercel.app/images/Logo.png" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="The Palm Hotel | Luxury Stay in Kafr El Sheikh" />
  <meta name="twitter:description" content="Luxury rooms, dining, events, and premium hospitality in Kafr El Sheikh." />
  <meta name="twitter:image" content="https://the-palm.vercel.app/images/Logo.png" />
  <link rel="canonical" href="https://the-palm.vercel.app/" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,400;1,9..144,500&family=Work+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
</svelte:head>

<div class="app">
  <Header />
  <Hero on:open-booking={openBookingModal} />
  <Overview />
  <Location />
  <Reception />
  <Rooms on:open-booking={openBookingModal} />
  <Dining />
  <Events />
  <Kids />
  <Amenities />
  <Offers />
  <Guests />
  <Policies />
  <CTA on:open-booking={openBookingModal} />
  <Footer />
  <BookingModal open={bookingOpen} roomType={bookingRoomType} guests={bookingGuests} on:close={closeBookingModal} />
</div>

<style>
  :global(:root) {
    --green-deep: #16332A;
    --green-darker: #0f251e;
    --green-emerald: #1e4539;
    --gold: #B8934A;
    --gold-light: #D8BD86;
    --sand: #F1EAD9;
    --sand-light: #FAF6EE;
  }

  :global(*) {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  :global(html) {
    scroll-behavior: smooth;
  }

  :global(body) {
    background: #F8F4EA;
    color: #201E19;
    font-family: 'Work Sans', sans-serif;
    overflow-x: hidden;
  }

  /* Custom Scrollbar Styles */
  :global(::-webkit-scrollbar) {
    width: 10px;
  }

  :global(::-webkit-scrollbar-track) {
    background: #F8F4EA;
  }

  :global(::-webkit-scrollbar-thumb) {
    background: linear-gradient(180deg, #B8934A 0%, #A77D34 100%);
    border-radius: 5px;
    border: 2px solid #F8F4EA;
  }

  :global(::-webkit-scrollbar-thumb:hover) {
    background: linear-gradient(180deg, #D8BD86 0%, #B8934A 100%);
  }

  :global(::-webkit-scrollbar-thumb:active) {
    background: linear-gradient(180deg, #A77D34 0%, #8B6B2E 100%);
  }

  /* Firefox Scrollbar */
  :global(*) {
    scrollbar-width: thin;
    scrollbar-color: #B8934A #F8F4EA;
  }

  .app {
    min-height: 100vh;
  }
</style>
