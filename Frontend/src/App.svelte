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
</script>

<svelte:head>
  <title>The Palm Hotel | Luxury in Kafr El Sheikh</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,400;1,9..144,500&family=Work+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
</svelte:head>

<div class="app">
  <Header />
  <Hero />
  <Overview />
  <Location />
  <Reception />
  <Rooms />
  <Dining />
  <Events />
  <Kids />
  <Amenities />
  <Offers />
  <Guests />
  <Policies />
  <CTA />
  <Footer />
</div>

<style>
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

  .app {
    min-height: 100vh;
  }
</style>
