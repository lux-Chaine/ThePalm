<script>
  import { reveal } from '../../actions/reveal.js';

  const policies = [
    {
      title: 'Check-in / Check-out',
      description: 'Check-in from 14:00, check-out until 12:00. Early check-in and late check-out are offered subject to availability.'
    },
    {
      title: 'Cancellation',
      description: 'Free cancellation up to 48 hours before arrival for standard-rate bookings; non-refundable rates are noted at booking.'
    },
    {
      title: 'Children &amp; Extra Beds',
      description: 'Children of all ages are welcome. Extra beds and cots are available on request, subject to room capacity and a surcharge.'
    },
    {
      title: 'Payment &amp; ID',
      description: 'Visa and Mastercard accepted alongside cash. A valid national ID or passport is required for check-in.'
    },
    {
      title: 'Pets',
      description: 'For the comfort of all guests, pets are not permitted within the hotel premises.'
    },
    {
      title: 'Smoking',
      description: 'The Palm is a non-smoking property; designated outdoor smoking areas are available.'
    }
  ];
</script>

<section class="policies">
  <div class="section-head" use:reveal>
    <span class="eyebrow">Good to Know</span>
    <h2>Policies at a glance.</h2>
  </div>
  <div class="policy-grid">
    {#each policies as policy, index}
      <div class="policy-card" use:reveal={{ delay: (index + 1) * 80 }}>
        <h4>{@html policy.title}</h4>
        <p>{policy.description}</p>
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

  .policies {
    background: var(--paper);
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

  .policy-grid {
    display: grid;
    grid-template-columns: repeat(3,1fr);
    gap: 28px;
  }

  .policy-card {
    border-top: 2px solid var(--gold);
    padding-top: 22px;
  }

  .policy-card h4 {
    font-family: 'Fraunces', serif;
    font-size: 1.1rem;
    margin-bottom: 12px;
    color: var(--ink);
  }

  .policy-card p {
    color: var(--ink-soft);
    font-size: 0.9rem;
    line-height: 1.65;
  }

  @media (max-width: 900px) {
    .policy-grid {
      grid-template-columns: 1fr 1fr;
      gap: 24px;
    }
    section {
      padding: 80px 6vw;
    }
  }

  @media (max-width: 540px) {
    .policy-grid {
      grid-template-columns: 1fr;
    }
  }
</style>
