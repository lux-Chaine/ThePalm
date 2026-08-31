<script>
  import { reveal } from '../../actions/reveal.js';
</script>

<section class="kids">
  <div class="kids-grid">
    <div class="kids-gallery" use:reveal>
      <img src="https://the-palm.vercel.app/images/palm43.jpg" alt="Kids zone">
      <img src="https://the-palm.vercel.app/images/palm42.jpg" alt="Kids zone">
      <img src="https://the-palm.vercel.app/images/palm41.jpg" alt="Kids zone">
    </div>
    <div class="kids-copy" use:reveal={{ delay: 200 }}>
      <span class="eyebrow">Family &amp; Leisure</span>
      <h3>The Palm Kids Zone</h3>
      <p>A safe, creative space for our younger guests, with professional supervision on hand so the whole family can relax.</p>
      <span class="note">Service chargeable</span>
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

  .kids {
    background: var(--paper);
  }

  .kids-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
  }

  .kids-gallery {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 10px;
  }

  .kids-gallery img {
    width: 100%;
    height: 160px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 16px 32px -18px rgba(22,51,42,0.25);
    transition: transform 1.2s cubic-bezier(.16,1,.3,1);
  }

  .kids-gallery img:hover {
    transform: scale(1.05);
  }

  .eyebrow {
    font-family: 'Work Sans', sans-serif;
    font-size: 0.72rem;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    font-weight: 600;
    color: var(--gold);
    display: block;
    margin-bottom: 18px;
  }

  .kids-copy h3 {
    font-family: 'Fraunces', serif;
    font-size: 1.9rem;
    margin-bottom: 14px;
    color: var(--ink);
  }

  .kids-copy p {
    color: var(--ink-soft);
    line-height: 1.75;
    margin-bottom: 14px;
  }

  .kids-copy .note {
    font-size: 0.85rem;
    color: var(--gold);
    font-weight: 600;
    letter-spacing: 0.03em;
    text-transform: uppercase;
  }

  @media (max-width: 900px) {
    .kids-grid {
      grid-template-columns: 1fr;
      gap: 36px;
    }
    .kids-gallery {
      grid-template-columns: 1fr 1fr;
    }
    section {
      padding: 80px 6vw;
    }
  }

  @media (max-width: 480px) {
    .kids-gallery {
      grid-template-columns: 1fr 1fr 1fr;
    }

    .kids-gallery img {
      height: 120px;
    }
  }
</style>
