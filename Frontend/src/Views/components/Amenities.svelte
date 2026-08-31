<script>
  import { reveal } from '../../actions/reveal.js';

  const amenities = [
    {
      title: 'Front Desk',
      description: 'Staffed around the clock in Arabic and English, with luggage storage and wake-up calls on request.'
    },
    {
      title: 'Free WiFi',
      description: 'High-speed wireless internet throughout all rooms, public areas, and event spaces.'
    },
    {
      title: 'Free Parking',
      description: 'Private on-site parking for guests and event attendees, available at no extra charge.'
    },
    {
      title: 'Airport Transfer',
      description: 'Shuttle service to Cairo and Borg El Arab airports arranged through the front desk, surcharge applies.'
    },
    {
      title: 'Laundry &amp; Dry Cleaning',
      description: 'Same-day laundry and pressing service available Sunday through Thursday.'
    },
    {
      title: 'Currency Exchange',
      description: 'On-site currency exchange and ATM access in the main lobby.'
    },
    {
      title: 'Room Service',
      description: 'In-room dining available during Main Restaurant hours, 07:00 to 23:00.'
    },
    {
      title: 'Business Centre',
      description: 'Printing, photocopying, and fax services, plus meeting-ready workstations near the lobby.'
    }
  ];
</script>

<section class="amenities">
  <div class="section-head" use:reveal>
    <span class="eyebrow">Facilities &amp; Services</span>
    <h2>Everything a stay calls for.</h2>
  </div>
  <div class="amenity-grid">
    {#each amenities as amenity, index}
      <div class="amenity-card" use:reveal={{ delay: (index + 1) * 80 }}>
        <h4>{@html amenity.title}</h4>
        <p>{amenity.description}</p>
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

  .amenities {
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

  .amenity-grid {
    display: grid;
    grid-template-columns: repeat(4,1fr);
    gap: 2px;
    background: rgba(74,73,63,0.15);
    border: 1px solid rgba(74,73,63,0.15);
  }

  .amenity-card {
    background: var(--sand);
    padding: 32px 26px;
  }

  .amenity-card h4 {
    font-family: 'Fraunces', serif;
    font-size: 1.05rem;
    margin-bottom: 10px;
    color: var(--green-mid);
  }

  .amenity-card p {
    color: var(--ink-soft);
    font-size: 0.86rem;
    line-height: 1.6;
  }

  @media (max-width: 900px) {
    .amenity-grid {
      grid-template-columns: 1fr 1fr;
    }
    section {
      padding: 80px 6vw;
    }
  }

  @media (max-width: 480px) {
    .amenity-grid {
      grid-template-columns: 1fr;
    }

    .amenity-card {
      padding: 24px 20px;
    }
  }
</style>
