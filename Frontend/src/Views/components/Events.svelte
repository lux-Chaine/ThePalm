<script>
  import { reveal } from '../../actions/reveal.js';

  const venues = [
    { name: 'Grand Ballroom', area: '180 m²', capacity: '250 guests' },
    { name: 'Palm Meeting Room', area: '60 m²', capacity: '60 guests' },
    { name: 'Boardroom', area: '35 m²', capacity: '20 guests' }
  ];
</script>

<section class="events" id="meetings">
  <div class="events-grid">
    <div class="events-gallery" use:reveal>
      <img src="https://the-palm.vercel.app/images/palm36.jpg" alt="Banquet room">
      <img src="https://the-palm.vercel.app/images/palm37.jpeg" alt="Banquet room">
      <img src="https://the-palm.vercel.app/images/palm11.jpeg" alt="Conference room">
      <img src="https://the-palm.vercel.app/images/palm12.jpeg" alt="Conference room">
    </div>
    <div class="events-copy" use:reveal={{ delay: 200 }}>
      <span class="eyebrow">Events &amp; Conferences</span>
      <h3>An elegant banquet room for every occasion.</h3>
      <p>From weddings to grand galas and corporate conferences, our banquet and meeting spaces flex to fit &mdash; with attentive service throughout.</p>
      <div class="capacity-grid">
        <div><strong>120</strong><span>Cocktail</span></div>
        <div><strong>100</strong><span>Auditorium</span></div>
        <div><strong>80</strong><span>Classroom</span></div>
        <div><strong>60</strong><span>U-Shape</span></div>
        <div><strong>110</strong><span>Theatre</span></div>
      </div>
      <div class="max-cap">
        <div><span>Max Capacity</span><strong>250 Guests</strong></div>
        <div><span>Availability</span><strong>24/7 Booking</strong></div>
      </div>
      <div class="venue-table">
        <div class="venue-row venue-head"><span>Venue</span><span>Area</span><span>Max Capacity</span></div>
        {#each venues as venue}
          <div class="venue-row">
            <span>{venue.name}</span>
            <span>{@html venue.area}</span>
            <span>{venue.capacity}</span>
          </div>
        {/each}
      </div>
    </div>
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

  .events {
    background: var(--green-deep);
  }

  .events-grid {
    display: grid;
    grid-template-columns: 1.1fr 1fr;
    gap: 60px;
    align-items: center;
  }

  .events-gallery {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }

  .events-gallery img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 20px 40px -20px rgba(0,0,0,0.5);
    transition: transform 1.2s cubic-bezier(.16,1,.3,1);
  }

  .events-gallery img:hover {
    transform: scale(1.05);
  }

  .eyebrow {
    font-family: 'Work Sans', sans-serif;
    font-size: 0.72rem;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    font-weight: 600;
    color: var(--gold-light);
    display: block;
    margin-bottom: 18px;
  }

  .events-copy h3 {
    color: var(--sand);
    font-family: 'Fraunces', serif;
    font-size: 1.9rem;
    margin-bottom: 18px;
  }

  .events-copy p {
    color: rgba(241,234,217,0.7);
    line-height: 1.75;
    margin-bottom: 30px;
  }

  .capacity-grid {
    display: grid;
    grid-template-columns: repeat(3,1fr);
    gap: 20px;
  }

  .capacity-grid div {
    border-top: 1px solid rgba(184,147,74,0.3);
    padding-top: 14px;
  }

  .capacity-grid strong {
    display: block;
    color: var(--gold-light);
    font-family: 'Fraunces', serif;
    font-size: 1.5rem;
  }

  .capacity-grid span {
    color: rgba(241,234,217,0.55);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
  }

  .max-cap {
    display: flex;
    gap: 40px;
    margin-top: 34px;
  }

  .max-cap div span {
    display: block;
    font-size: 0.72rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--gold-light);
    margin-bottom: 6px;
  }

  .max-cap div strong {
    font-family: 'Fraunces', serif;
    color: var(--sand);
    font-size: 1.3rem;
  }

  .venue-table {
    margin-top: 44px;
    border-top: 1px solid rgba(184,147,74,0.25);
  }

  .venue-row {
    display: grid;
    grid-template-columns: 1.6fr 1fr 1fr;
    gap: 16px;
    padding: 14px 0;
    border-bottom: 1px solid rgba(184,147,74,0.15);
    font-size: 0.88rem;
    color: rgba(241,234,217,0.75);
  }

  .venue-row span:first-child {
    color: var(--sand);
    font-family: 'Fraunces', serif;
  }

  .venue-head {
    font-size: 0.68rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--gold-light);
    border-bottom: 1px solid var(--gold);
  }

  .venue-head span:first-child {
    font-family: 'Work Sans', sans-serif;
    color: var(--gold-light);
  }

  @media (max-width: 900px) {
    .events-grid {
      grid-template-columns: 1fr;
    }
    .capacity-grid {
      grid-template-columns: repeat(2,1fr);
    }
    section {
      padding: 80px 6vw;
    }
  }
</style>
