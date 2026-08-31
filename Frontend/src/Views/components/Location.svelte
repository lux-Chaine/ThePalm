<script>
  import { reveal } from '../../actions/reveal.js';
</script>

<section class="location" id="location">
  <div class="section-head" use:reveal>
    <span class="eyebrow">Perfectly Situated</span>
    <h2>Everything within easy reach.</h2>
  </div>
  <div class="nearby-grid">
    <div class="nearby-card" use:reveal={{ delay: 100 }}>
      <img src="https://the-palm.vercel.app/images/palm4.jpg" alt="Kafr El Sheikh University">
      <div class="label"><h3>Kafr El Sheikh University</h3><span>0.8 km from hotel</span></div>
    </div>
    <div class="nearby-card" use:reveal={{ delay: 200 }}>
      <img src="https://the-palm.vercel.app/images/palm6.jpg" alt="St. Demiana Church">
      <div class="label"><h3>St. Demiana Church</h3><span>1.2 km from hotel</span></div>
    </div>
    <div class="nearby-card" use:reveal={{ delay: 300 }}>
      <img src="https://the-palm.vercel.app/images/palm5.jpg" alt="Kafr El Sheikh Museum">
      <div class="label"><h3>Kafr El Sheikh Museum</h3><span>2.1 km from hotel</span></div>
    </div>
    <div class="nearby-card" use:reveal={{ delay: 400 }}>
      <img src="https://the-palm.vercel.app/images/palm2.jpeg" alt="Central Kafr El Sheikh">
      <div class="label"><h3>City Centre &amp; Souq</h3><span>1.5 km from hotel</span></div>
    </div>
  </div>

  <div class="distance-table" use:reveal={{ delay: 100 }}>
    <div class="distance-row distance-head"><span>Getting Around</span><span>Distance</span><span>Approx. Travel Time</span></div>
    <div class="distance-row"><span>Kafr El Sheikh Train Station</span><span>1.4 km</span><span>5 min by car</span></div>
    <div class="distance-row"><span>Tanta City</span><span>45 km</span><span>40 min by car</span></div>
    <div class="distance-row"><span>Alexandria</span><span>95 km</span><span>1 h 15 min by car</span></div>
    <div class="distance-row"><span>Cairo International Airport (CAI)</span><span>145 km</span><span>2 h by car</span></div>
    <div class="distance-row"><span>Borg El Arab Airport (HBE)</span><span>110 km</span><span>1 h 30 min by car</span></div>
  </div>

  <div class="facilities-row" use:reveal={{ delay: 200 }}>
    <span class="facility-pill">Non-smoking</span>
    <span class="facility-pill">Restaurant</span>
    <span class="facility-pill">Room Service</span>
    <span class="facility-pill">Free Parking</span>
    <span class="facility-pill">Free WiFi</span>
    <span class="facility-pill">Tea / Coffee</span>
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

  .location {
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

  .nearby-grid {
    display: grid;
    grid-template-columns: repeat(4,1fr);
    gap: 24px;
    margin-bottom: 56px;
  }

  .nearby-card {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    aspect-ratio: 4/5;
    box-shadow: 0 20px 40px -18px rgba(22,51,42,0.35);
  }

  .nearby-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 1.2s cubic-bezier(.16,1,.3,1);
  }

  .nearby-card:hover img {
    transform: scale(1.06);
  }

  .nearby-card::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 40%, rgba(22,51,42,0.9) 100%);
  }

  .nearby-card .label {
    position: absolute;
    left: 20px;
    right: 20px;
    bottom: 18px;
    z-index: 2;
    color: var(--sand);
  }

  .nearby-card .label h3 {
    font-family: 'Fraunces', serif;
    font-size: 1.1rem;
    margin-bottom: 4px;
    color: var(--sand);
  }

  .nearby-card .label span {
    font-size: 0.78rem;
    color: var(--gold-light);
    letter-spacing: 0.04em;
  }

  .distance-table {
    margin-bottom: 48px;
    border-top: 1px solid rgba(74,73,63,0.15);
  }

  .distance-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    gap: 20px;
    padding: 16px 4px;
    border-bottom: 1px solid rgba(74,73,63,0.12);
    font-size: 0.9rem;
    color: var(--ink-soft);
  }

  .distance-row span:first-child {
    color: var(--ink);
    font-weight: 500;
  }

  .distance-row span:last-child {
    text-align: right;
  }

  .distance-head {
    font-size: 0.72rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--gold);
    font-weight: 600;
    border-bottom: 1px solid var(--gold);
  }

  .facilities-row {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
  }

  .facility-pill {
    background: var(--paper);
    border: 1px solid rgba(74,73,63,0.15);
    padding: 12px 22px;
    border-radius: 30px;
    font-size: 0.85rem;
    color: var(--ink-soft);
  }

  @media (max-width: 900px) {
    .nearby-grid {
      grid-template-columns: 1fr 1fr;
    }
    .distance-row {
      grid-template-columns: 1.4fr 0.8fr 1fr;
      font-size: 0.8rem;
    }
    section {
      padding: 80px 6vw;
    }
  }

  @media (max-width: 600px) {
    .nearby-grid {
      grid-template-columns: 1fr;
    }

    .distance-row {
      grid-template-columns: 1fr 1fr;
      gap: 10px;
    }

    .distance-row span:last-child {
      grid-column: 1 / -1;
      text-align: left;
      color: var(--ink-soft);
      font-style: italic;
    }

    .distance-head span:last-child {
      display: none;
    }

    .facilities-row {
      gap: 10px;
    }

    .facility-pill {
      padding: 10px 16px;
      font-size: 0.8rem;
    }
  }
</style>
