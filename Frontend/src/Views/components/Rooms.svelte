<script>
  import { reveal } from '../../actions/reveal.js';
  import { t } from '../../i18n/index.js';

  const images = [
    ['https://the-palm.vercel.app/images/palm7.jpeg','https://the-palm.vercel.app/images/palm8.jpeg'],
    ['https://the-palm.vercel.app/images/palm7.jpeg','https://the-palm.vercel.app/images/palm8.jpeg'],
    ['https://the-palm.vercel.app/images/palm9.jpeg','https://the-palm.vercel.app/images/palm10.jpeg'],
    ['https://the-palm.vercel.app/images/palm37.jpeg','https://the-palm.vercel.app/images/palm36.jpg'],
  ];
  const reverseIndex = [false, true, false, true];
</script>

<section class="rooms" id="rooms">
  <div class="section-head on-dark" use:reveal>
    <span class="eyebrow">{$t.rooms.eyebrow}</span>
    <h2>{$t.rooms.title}</h2>
  </div>

  {#each $t.rooms.list as room, i}
    <div class="room-block {reverseIndex[i] ? 'reverse' : ''}" use:reveal={{ delay: (i + 1) * 100 }}>
      <div class="room-photos">
        {#each images[i] as img}
          <img src={img} alt={room.name}>
        {/each}
      </div>
      <div class="room-copy">
        <span class="size">{room.size}</span>
        <h3>{room.name}</h3>
        <span class="config">{room.config}</span>
        <p>{room.description}</p>
        <div class="room-meta">
          <div><span>{$t.rooms.meta.occupancy}</span><strong>{room.occupancy}</strong></div>
          <div><span>{$t.rooms.meta.view}</span><strong>{room.view}</strong></div>
          <div><span>{$t.rooms.meta.floor}</span><strong>{room.floor}</strong></div>
        </div>
        <div class="amen-tags">
          {#each room.amenities as a}<span>{a}</span>{/each}
        </div>
      </div>
    </div>
  {/each}

  <p class="policy-note" use:reveal={{ delay: 100 }}>{$t.rooms.policyNote}</p>
</section>

<style>
  :root {
    --green-deep: #16332A; --green-mid: #234A3B; --gold: #B8934A;
    --gold-light: #D8BD86; --sand: #F1EAD9; --paper: #F8F4EA;
    --ink: #201E19; --ink-soft: #4A493F;
  }
  section { position: relative; padding: 130px max(6vw, calc((100vw - 1260px)/2)); }
  .rooms { background: var(--green-deep); }
  .section-head { max-width: 640px; margin-bottom: 64px; }
  .section-head::before { content: ''; display: block; width: 54px; height: 2px; background: var(--gold); margin-bottom: 22px; }
  .section-head.on-dark::before { background: var(--gold-light); }
  .section-head.on-dark .eyebrow { color: var(--gold-light); }
  .section-head.on-dark h2 { color: var(--sand); }
  .eyebrow { font-family: 'Work Sans', sans-serif; font-size: 0.72rem; letter-spacing: 0.22em; text-transform: uppercase; font-weight: 600; color: var(--gold); margin-bottom: 16px; display: block; }
  h2 { font-family: 'Fraunces', serif; font-weight: 500; letter-spacing: -0.01em; line-height: 1.08; font-size: clamp(2rem, 3.4vw, 2.9rem); }
  .room-block { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; margin-bottom: 90px; }
  .room-block:last-of-type { margin-bottom: 0; }
  .room-block.reverse .room-photos { order: 2; }
  .room-photos { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
  .room-photos img { width: 100%; height: 280px; object-fit: cover; border-radius: 8px; box-shadow: 0 24px 50px -22px rgba(0,0,0,0.5); transition: transform 1.2s cubic-bezier(.16,1,.3,1); }
  .room-photos img:hover { transform: scale(1.04); }
  .room-copy .size { color: var(--gold-light); font-family: 'Fraunces', serif; font-style: italic; font-size: 0.95rem; display: block; margin-bottom: 10px; }
  .room-copy h3 { color: var(--sand); font-family: 'Fraunces', serif; font-size: 1.9rem; margin-bottom: 8px; }
  .room-copy .config { color: var(--gold); font-size: 0.85rem; letter-spacing: 0.03em; margin-bottom: 20px; display: block; }
  .room-copy p { color: rgba(241,234,217,0.7); line-height: 1.75; margin-bottom: 22px; }
  .room-meta { display: flex; gap: 32px; margin-bottom: 26px; padding-bottom: 26px; border-bottom: 1px solid rgba(184,147,74,0.25); flex-wrap: wrap; }
  .room-meta div span { display: block; font-size: 0.68rem; letter-spacing: 0.08em; text-transform: uppercase; color: var(--gold-light); margin-bottom: 5px; }
  .room-meta div strong { font-family: 'Fraunces', serif; color: var(--sand); font-size: 0.98rem; font-weight: 400; }
  .amen-tags { display: flex; flex-wrap: wrap; gap: 10px; }
  .amen-tags span { font-size: 0.75rem; letter-spacing: 0.04em; text-transform: uppercase; color: var(--sand); border: 1px solid rgba(184,147,74,0.4); padding: 7px 14px; border-radius: 2px; }
  .policy-note { margin-top: 60px; padding-top: 36px; border-top: 1px solid rgba(184,147,74,0.25); color: rgba(241,234,217,0.55); font-size: 0.9rem; font-style: italic; }

  @media (max-width: 900px) {
    .room-block, .room-block.reverse { grid-template-columns: 1fr; gap: 36px; }
    .room-block.reverse .room-photos { order: 0; }
    section { padding: 80px 6vw; }
  }
  @media (max-width: 600px) {
    .room-photos { grid-template-columns: 1fr; }
    .room-photos img { height: 240px; }
    .room-meta { gap: 20px; }
    .room-block { margin-bottom: 60px; }
  }
</style>
