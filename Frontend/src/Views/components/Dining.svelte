<script>
  import { reveal } from '../../actions/reveal.js';

  const outlets = [
    {
      name: 'Main Restaurant',
      description: 'International buffet breakfast alongside a-la-carte dining, pairing local flavors with world classics, right off the lobby.',
      cuisine: 'International &amp; Egyptian',
      hours: '07:00 &ndash; 23:00',
      breakfast: '07:00 &ndash; 11:00',
      location: 'Ground Floor'
    },
    {
      name: 'White Garden',
      description: 'An open-air courtyard café for shaded lunches, afternoon coffee, and shisha evenings under string lights.',
      cuisine: 'Café &amp; Light Bites',
      hours: '12:00 &ndash; 00:00',
      seating: 'Outdoor Courtyard',
      location: 'Ground Floor Garden'
    },
    {
      name: 'Blue Sky Roof',
      description: 'Rooftop dining and shisha lounge with panoramic views over Kafr El Sheikh, best enjoyed at sunset.',
      cuisine: 'Grill &amp; Shisha Lounge',
      hours: '16:00 &ndash; 01:00',
      seating: 'Open-Air Rooftop',
      location: 'Top Floor'
    }
  ];
</script>

<section class="dining" id="dining">
  <div class="section-head" use:reveal>
    <span class="eyebrow">Foods &amp; Beverages</span>
    <h2>Dining, from breakfast to sunset.</h2>
  </div>
  <div class="dining-gallery" use:reveal={{ delay: 100 }}>
    <img src="https://the-palm.vercel.app/images/palm23.jpeg" alt="Restaurant view">
    <img src="https://the-palm.vercel.app/images/palm20.jpg" alt="Restaurant view">
    <img src="https://the-palm.vercel.app/images/palm22.jpg" alt="Restaurant view">
    <img src="https://the-palm.vercel.app/images/palm24.jpeg" alt="Restaurant view">
  </div>

  <div class="outlet-grid">
    {#each outlets as outlet, index}
      <div class="outlet-card" use:reveal={{ delay: (index + 2) * 100 }}>
        <h3>{outlet.name}</h3>
        <p>{outlet.description}</p>
        <dl class="outlet-meta">
          <div><dt>Cuisine</dt><dd>{@html outlet.cuisine}</dd></div>
          <div><dt>Hours</dt><dd>{@html outlet.hours}</dd></div>
          {#if outlet.breakfast}
            <div><dt>Breakfast</dt><dd>{@html outlet.breakfast}</dd></div>
          {/if}
          {#if outlet.seating}
            <div><dt>Seating</dt><dd>{@html outlet.seating}</dd></div>
          {/if}
          <div><dt>Location</dt><dd>{outlet.location}</dd></div>
        </dl>
      </div>
    {/each}
  </div>
</section>

<style>
  :root {
    --green-deep: #16332A;
    --green-mid: #234A3B;
    --gold: #B8934A;
    --gold-light: #D8BD86;
    --sand: #F1EAD9;
    --sand-warm: #EAE1CB;
    --paper: #F8F4EA;
    --ink: #201E19;
    --ink-soft: #4A493F;
  }

  section {
    position: relative;
    padding: 130px max(6vw, calc((100vw - 1260px)/2));
  }

  .dining {
    background: var(--sand);
  }

  .section-head {
    max-width: 640px;
    margin-bottom: 64px;
  }

  .section-head::before {
    content: '';
    display: block;
    width: 54px;
    height: 2px;
    background: var(--gold);
    margin-bottom: 22px;
  }

  .eyebrow {
    font-family: 'Work Sans', sans-serif;
    font-size: 0.72rem;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    font-weight: 600;
    color: var(--gold);
    margin-bottom: 16px;
    display: block;
  }

  h2 {
    font-family: 'Fraunces', serif;
    font-weight: 500;
    letter-spacing: -0.01em;
    line-height: 1.08;
    font-size: clamp(2rem, 3.4vw, 2.9rem);
    color: var(--ink);
  }

  .dining-gallery {
    display: grid;
    grid-template-columns: repeat(4,1fr);
    gap: 14px;
    margin-bottom: 56px;
  }

  .dining-gallery img {
    width: 100%;
    height: 210px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 18px 36px -18px rgba(22,51,42,0.28);
    transition: transform 1.2s cubic-bezier(.16,1,.3,1);
  }

  .dining-gallery img:hover {
    transform: scale(1.05);
  }

  .outlet-grid {
    display: grid;
    grid-template-columns: repeat(3,1fr);
    gap: 28px;
  }

  .outlet-card {
    background: var(--paper);
    border-radius: 8px;
    padding: 32px 28px;
    box-shadow: 0 20px 40px -26px rgba(22,51,42,0.25);
  }

  .outlet-card h3 {
    font-family: 'Fraunces', serif;
    font-size: 1.3rem;
    margin-bottom: 12px;
    color: var(--ink);
  }

  .outlet-card p {
    color: var(--ink-soft);
    line-height: 1.65;
    font-size: 0.92rem;
    margin-bottom: 24px;
    min-height: 78px;
  }

  .outlet-meta {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    border-top: 1px solid rgba(74,73,63,0.15);
    padding-top: 20px;
  }

  .outlet-meta dt {
    font-size: 0.68rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 4px;
  }

  .outlet-meta dd {
    font-family: 'Fraunces', serif;
    font-size: 0.95rem;
    color: var(--ink);
  }

  @media (max-width: 900px) {
    .dining-gallery {
      grid-template-columns: repeat(2,1fr);
    }
    .outlet-grid {
      grid-template-columns: 1fr;
    }
    section {
      padding: 80px 6vw;
    }
  }

  @media (max-width: 480px) {
    .dining-gallery {
      grid-template-columns: 1fr 1fr;
      gap: 10px;
    }

    .dining-gallery img {
      height: 160px;
    }

    .outlet-meta {
      grid-template-columns: 1fr;
    }
  }
</style>
