<script>
  import { reveal } from '../../actions/reveal.js';

  const offers = [
    {
      tag: 'Popular',
      title: 'Bed &amp; Breakfast',
      description: 'Room rate inclusive of our international buffet breakfast at the Main Restaurant, served daily from 07:00.',
      email: 'reservation@chain-luxe.com'
    },
    {
      tag: 'Save More',
      title: 'Stay Longer',
      description: 'Book three consecutive nights or more and receive a complimentary late check-out until 16:00, subject to availability.',
      email: 'reservation@chain-luxe.com'
    },
    {
      tag: 'For Business',
      title: 'Corporate Rate',
      description: 'Preferential rates for corporate accounts and long-stay business travelers, with flexible billing on request.',
      email: 'reservation@chain-luxe.com'
    },
    {
      tag: 'Celebrations',
      title: 'Events Package',
      description: 'Bundle the Grand Ballroom with catering from our dining team for weddings, conferences, and family celebrations.',
      email: 'reservation@chain-luxe.com'
    }
  ];
</script>

<section class="offers" id="offers">
  <div class="section-head" use:reveal>
    <span class="eyebrow">Offers &amp; Packages</span>
    <h2>Tailored ways to stay with us.</h2>
  </div>
  <div class="offer-grid">
    {#each offers as offer, index}
      <div class="offer-card" use:reveal={{ delay: (index + 1) * 100 }}>
        <span class="offer-tag">{offer.tag}</span>
        <h3>{@html offer.title}</h3>
        <p>{offer.description}</p>
        <a href="mailto:{offer.email}" class="offer-link">Enquire &rarr;</a>
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

  .offers {
    background: var(--green-deep);
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
    color: var(--gold-light);
    margin-bottom: 16px;
    display: block;
  }

  h2 {
    font-family: 'Fraunces', serif;
    font-weight: 500;
    letter-spacing: -0.01em;
    line-height: 1.08;
    font-size: clamp(2rem, 3.4vw, 2.9rem);
    color: var(--sand);
  }

  .offer-grid {
    display: grid;
    grid-template-columns: repeat(4,1fr);
    gap: 24px;
  }

  .offer-card {
    position: relative;
    background: rgba(241,234,217,0.05);
    border: 1px solid rgba(184,147,74,0.3);
    border-radius: 8px;
    padding: 32px 26px;
  }

  .offer-tag {
    display: inline-block;
    font-size: 0.65rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--green-deep);
    background: var(--gold-light);
    padding: 5px 12px;
    border-radius: 20px;
    font-weight: 600;
    margin-bottom: 18px;
  }

  .offer-card h3 {
    color: var(--sand);
    font-family: 'Fraunces', serif;
    font-size: 1.25rem;
    margin-bottom: 14px;
  }

  .offer-card p {
    color: rgba(241,234,217,0.65);
    font-size: 0.9rem;
    line-height: 1.65;
    margin-bottom: 22px;
    min-height: 96px;
  }

  .offer-link {
    color: var(--gold-light);
    font-size: 0.82rem;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    border-bottom: 1px solid rgba(184,147,74,0.4);
    padding-bottom: 3px;
  }

  @media (max-width: 900px) {
    .offer-grid {
      grid-template-columns: 1fr 1fr;
    }
    section {
      padding: 80px 6vw;
    }
  }
</style>
