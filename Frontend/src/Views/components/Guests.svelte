<script>
  import { reveal } from '../../actions/reveal.js';

  const guests = [
    { name: 'Mohamed Almogtaba', role: 'Guest', image: 'https://the-palm.vercel.app/images/palm14.jpg' },
    { name: 'Abdullah', role: 'Guest', image: 'https://the-palm.vercel.app/images/palm17.jpg' },
    { name: 'Ahmed', role: 'Guest', image: 'https://the-palm.vercel.app/images/palm15.jpg' },
    { name: 'Hussain', role: 'Guest', image: 'https://the-palm.vercel.app/images/palm16.jpg' }
  ];
</script>

<section class="guests">
  <div class="section-head" use:reveal>
    <span class="eyebrow">Guest Experiences</span>
    <h2>Stories from those who've stayed.</h2>
  </div>
  <div class="rating-strip" use:reveal={{ delay: 100 }}>
    <div><strong>9.1</strong><span>Overall</span></div>
    <div><strong>9.3</strong><span>Staff</span></div>
    <div><strong>8.9</strong><span>Cleanliness</span></div>
    <div><strong>9.0</strong><span>Location</span></div>
    <div><strong>8.8</strong><span>Value</span></div>
  </div>
  <div class="guest-grid">
    {#each guests as guest, index}
      <div class="guest-card" use:reveal={{ delay: (index + 2) * 100 }}>
        <img src={guest.image} alt={guest.name}>
        <span>{guest.name}</span>
        <small>{guest.role}</small>
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

  .guests {
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

  .guest-grid {
    display: grid;
    grid-template-columns: repeat(4,1fr);
    gap: 24px;
  }

  .guest-card {
    text-align: center;
  }

  .guest-card img {
    width: 100%;
    aspect-ratio: 1/1;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 16px;
    box-shadow: 0 16px 32px -16px rgba(22,51,42,0.3);
  }

  .guest-card span {
    font-family: 'Fraunces', serif;
    font-style: italic;
    font-size: 1rem;
    color: var(--ink);
  }

  .guest-card small {
    display: block;
    color: var(--ink-soft);
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    margin-top: 4px;
  }

  .rating-strip {
    display: flex;
    gap: 0;
    margin-bottom: 56px;
    flex-wrap: wrap;
  }

  .rating-strip div {
    padding-right: 44px;
    margin-right: 44px;
    border-right: 1px solid rgba(74,73,63,0.15);
  }

  .rating-strip div:last-child {
    border-right: none;
  }

  .rating-strip strong {
    display: block;
    font-family: 'Fraunces', serif;
    font-size: 1.7rem;
    color: var(--green-mid);
  }

  .rating-strip span {
    font-size: 0.72rem;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--ink-soft);
  }

  @media (max-width: 900px) {
    .guest-grid {
      grid-template-columns: 1fr 1fr;
    }
    .rating-strip div {
      padding-right: 24px;
      margin-right: 24px;
      margin-bottom: 14px;
    }
    section {
      padding: 80px 6vw;
    }
  }
</style>
