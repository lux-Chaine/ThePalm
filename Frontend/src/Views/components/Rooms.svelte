<script>
  import { reveal } from '../../actions/reveal.js';

  const rooms = [
    {
      size: '25 m²',
      name: 'Standard Room',
      config: 'King Bed (4 Rooms) &nbsp;&middot;&nbsp; Twin Bed (28 Rooms)',
      description: 'Sized for corporate travelers and short city breaks alike. Every Standard Room features premium Egyptian cotton linens and a dedicated workspace.',
      occupancy: '2 Adults',
      view: 'City / Courtyard',
      floor: '1st &ndash; 4th',
      amenities: ['A/C', 'Shower', 'Safe Box', 'WiFi', 'Mini Bar'],
      images: [
        'https://the-palm.vercel.app/images/palm7.jpeg',
        'https://the-palm.vercel.app/images/palm8.jpeg'
      ],
      reverse: false
    },
    {
      size: '32 m²',
      name: 'Superior Room',
      config: 'King or Twin Bed (10 Rooms)',
      description: 'A step up in space and outlook, with a seating nook by the window and upgraded bath amenities &mdash; well suited to longer stays.',
      occupancy: '2 Adults, 1 Child',
      view: 'Garden / Pool',
      floor: '2nd &ndash; 5th',
      amenities: ['A/C', 'Bathtub', 'Safe Box', 'WiFi', 'Mini Bar', 'Seating Area'],
      images: [
        'https://the-palm.vercel.app/images/palm7.jpeg',
        'https://the-palm.vercel.app/images/palm8.jpeg'
      ],
      reverse: true
    },
    {
      size: '45 m²',
      name: 'Junior Suite',
      config: 'Premium Suite Collection (4 Suites)',
      description: 'An expansive living area alongside a spacious master bedroom &mdash; built for guests who want extra room to settle in and unwind.',
      occupancy: '3 Adults',
      view: 'Garden',
      floor: '3rd &ndash; 5th',
      amenities: ['A/C', 'Shower', 'Safe Box', 'WiFi', 'Mini Bar', 'Living Area'],
      images: [
        'https://the-palm.vercel.app/images/palm9.jpeg',
        'https://the-palm.vercel.app/images/palm10.jpeg'
      ],
      reverse: false
    },
    {
      size: '60 m²',
      name: 'Executive Suite',
      config: 'Top-Floor Collection (2 Suites)',
      description: 'The Palm\'s most spacious address &mdash; a separate lounge, dining nook, and master bedroom, finished with upgraded furnishings for guests staying longer or hosting privately.',
      occupancy: '4 Adults',
      view: 'City Panorama',
      floor: 'Top Floor',
      amenities: ['A/C', 'Bathtub', 'Safe Box', 'WiFi', 'Mini Bar', 'Dining Nook', 'Lounge'],
      images: [
        'https://the-palm.vercel.app/images/palm37.jpeg',
        'https://the-palm.vercel.app/images/palm36.jpg'
      ],
      reverse: true
    }
  ];
</script>

<section class="rooms" id="rooms">
  <div class="section-head on-dark" use:reveal>
    <span class="eyebrow">Exquisite Accommodation</span>
    <h2>Rooms &amp; suites, designed for comfort.</h2>
  </div>

  {#each rooms as room, index}
    <div class="room-block {room.reverse ? 'reverse' : ''}" use:reveal={{ delay: (index + 1) * 100 }}>
      <div class="room-photos">
        {#each room.images as image}
          <img src={image} alt={room.name}>
        {/each}
      </div>
      <div class="room-copy">
        <span class="size">{@html room.size}</span>
        <h3>{room.name}</h3>
        <span class="config">{@html room.config}</span>
        <p>{room.description}</p>
        <div class="room-meta">
          <div><span>Occupancy</span><strong>{room.occupancy}</strong></div>
          <div><span>View</span><strong>{@html room.view}</strong></div>
          <div><span>Floor</span><strong>{@html room.floor}</strong></div>
        </div>
        <div class="amen-tags">
          {#each room.amenities as amen}
            <span>{amen}</span>
          {/each}
        </div>
      </div>
    </div>
  {/each}

  <p class="policy-note" use:reveal={{ delay: 100 }}>Property policy: for the comfort of all guests, pets are not allowed within the hotel premises. Extra bed available on request, subject to room capacity and surcharge.</p>
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

  .rooms {
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

  .section-head.on-dark::before {
    background: var(--gold-light);
  }

  .section-head.on-dark .eyebrow {
    color: var(--gold-light);
  }

  .section-head.on-dark h2 {
    color: var(--sand);
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
  }

  .room-block {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
    margin-bottom: 90px;
  }

  .room-block:last-child {
    margin-bottom: 0;
  }

  .room-block.reverse .room-photos {
    order: 2;
  }

  .room-photos {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }

  .room-photos img {
    width: 100%;
    height: 280px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 24px 50px -22px rgba(0,0,0,0.5);
    transition: transform 1.2s cubic-bezier(.16,1,.3,1);
  }

  .room-photos img:hover {
    transform: scale(1.04);
  }

  .room-copy .size {
    color: var(--gold-light);
    font-family: 'Fraunces', serif;
    font-style: italic;
    font-size: 0.95rem;
    display: block;
    margin-bottom: 10px;
  }

  .room-copy h3 {
    color: var(--sand);
    font-size: 1.9rem;
    margin-bottom: 8px;
  }

  .room-copy .config {
    color: var(--gold);
    font-size: 0.85rem;
    letter-spacing: 0.03em;
    margin-bottom: 20px;
    display: block;
  }

  .room-copy p {
    color: rgba(241,234,217,0.7);
    line-height: 1.75;
    margin-bottom: 22px;
  }

  .room-meta {
    display: flex;
    gap: 32px;
    margin-bottom: 26px;
    padding-bottom: 26px;
    border-bottom: 1px solid rgba(184,147,74,0.25);
    flex-wrap: wrap;
  }

  .room-meta div span {
    display: block;
    font-size: 0.68rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--gold-light);
    margin-bottom: 5px;
  }

  .room-meta div strong {
    font-family: 'Fraunces', serif;
    color: var(--sand);
    font-size: 0.98rem;
    font-weight: 400;
  }

  .amen-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
  }

  .amen-tags span {
    font-size: 0.75rem;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: var(--sand);
    border: 1px solid rgba(184,147,74,0.4);
    padding: 7px 14px;
    border-radius: 2px;
  }

  .policy-note {
    margin-top: 60px;
    padding-top: 36px;
    border-top: 1px solid rgba(184,147,74,0.25);
    color: rgba(241,234,217,0.55);
    font-size: 0.9rem;
    font-style: italic;
  }

  @media (max-width: 900px) {
    .room-block, .room-block.reverse {
      grid-template-columns: 1fr;
    }
    .room-block.reverse .room-photos {
      order: 0;
    }
    section {
      padding: 80px 6vw;
    }
  }
</style>
