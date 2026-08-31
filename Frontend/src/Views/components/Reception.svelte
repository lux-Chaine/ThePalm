<script>
  import { reveal } from '../../actions/reveal.js';
  import { t } from '../../i18n/index.js';
</script>

<section class="reception">
  <div class="reception-grid">
    <div class="reception-photos" use:reveal>
      <a href="#" aria-label="Reception and lobby"><img src="https://the-palm.vercel.app/images/palm45.jpeg" alt="Reception and lobby"></a>
      <a href="#" aria-label="Lobby view"><img src="https://the-palm.vercel.app/images/palm44.jpg" alt="Lobby view"></a>
      <a href="#" aria-label="Lobby seating"><img src="https://the-palm.vercel.app/images/palm46.jpeg" alt="Lobby seating"></a>
    </div>
    <div use:reveal={{ delay: 200 }}>
      <span class="eyebrow">{$t.reception.eyebrow}</span>
      <h2>{$t.reception.title}</h2>
      <dl class="info-list">
        {#each Object.values($t.reception.info) as item}
          <div><dt>{item.label}</dt><dd>{item.value}</dd></div>
        {/each}
      </dl>
    </div>
  </div>
</section>

<style>
  :root {
    --green-deep: #16332A; --green-mid: #234A3B; --gold: #B8934A;
    --gold-light: #D8BD86; --sand: #F1EAD9; --paper: #F8F4EA;
    --ink: #201E19; --ink-soft: #4A493F;
  }
  section { position: relative; padding: 130px max(6vw, calc((100vw - 1260px)/2)); }
  .reception { background: var(--paper); }
  .reception-grid { display: grid; grid-template-columns: 1.1fr 1fr; gap: 70px; align-items: center; }
  .reception-photos { display: grid; grid-template-columns: 1.3fr 1fr; grid-template-rows: 1fr 1fr; gap: 14px; height: 460px; }
  .reception-photos img { width: 100%; height: 100%; object-fit: cover; border-radius: 8px; box-shadow: 0 20px 40px -20px rgba(22,51,42,0.3); transition: transform 1.2s cubic-bezier(.16,1,.3,1); }
  .reception-photos a { overflow: hidden; border-radius: 8px; display: block; }
  .reception-photos a:hover img { transform: scale(1.06); }
  .reception-photos a:first-child { grid-row: 1/3; }
  .eyebrow { font-family: 'Work Sans', sans-serif; font-size: 0.72rem; letter-spacing: 0.22em; text-transform: uppercase; font-weight: 600; color: var(--gold); display: block; margin-bottom: 18px; }
  h2 { font-family: 'Fraunces', serif; font-weight: 500; letter-spacing: -0.01em; line-height: 1.08; color: var(--ink); font-size: clamp(1.9rem, 3vw, 2.6rem); margin-bottom: 34px; }
  .info-list { display: grid; grid-template-columns: 1fr 1fr; gap: 32px 24px; }
  .info-list dt { font-size: 0.72rem; letter-spacing: 0.1em; text-transform: uppercase; color: var(--gold); margin-bottom: 8px; font-weight: 600; }
  .info-list dd { font-family: 'Fraunces', serif; font-size: 1.2rem; color: var(--ink); }

  @media (max-width: 900px) {
    .reception-grid { grid-template-columns: 1fr; gap: 40px; }
    .reception-photos { height: auto; grid-template-rows: none; grid-template-columns: 1fr 1fr; }
    .reception-photos a:first-child { grid-row: auto; }
    .info-list { grid-template-columns: 1fr 1fr; }
    section { padding: 80px 6vw; }
  }
  @media (max-width: 480px) {
    .reception-photos { grid-template-columns: 1fr; }
    .info-list { grid-template-columns: 1fr; gap: 20px; }
  }
</style>
