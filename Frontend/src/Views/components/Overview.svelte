<script>
  import { reveal } from '../../actions/reveal.js';
  import { t } from '../../i18n/index.js';
</script>

<section class="overview">
  <div class="overview-inner" use:reveal>
    <span class="eyebrow">{$t.overview.eyebrow}</span>
    <p class="overview-lead">{$t.overview.lead}</p>
    <div class="award-row">
      {#each Object.values($t.overview.awards) as award, i}
        <div class="award-item" use:reveal={{ delay: (i + 1) * 100 }}>
          <strong>{award.value}</strong><span>{award.label}</span>
        </div>
      {/each}
    </div>
  </div>
</section>

<style>
  :root {
    --green-deep: #16332A; --green-mid: #234A3B; --gold: #B8934A;
    --gold-light: #D8BD86; --sand: #F1EAD9; --paper: #F8F4EA;
    --ink: #201E19; --ink-soft: #4A493F;
  }
  .overview {
    background: var(--paper);
    padding: 96px max(6vw, calc((100vw - 1260px)/2)) 70px;
    text-align: center;
  }
  .overview-inner { max-width: 820px; margin: 0 auto; }
  .eyebrow {
    font-family: 'Work Sans', sans-serif; font-size: 0.72rem;
    letter-spacing: 0.22em; text-transform: uppercase; font-weight: 600;
    color: var(--gold); display: block; margin-bottom: 22px;
  }
  .overview-lead {
    font-family: 'Fraunces', serif; font-size: clamp(1.2rem, 2.1vw, 1.55rem);
    line-height: 1.55; color: var(--ink); margin-bottom: 52px;
  }
  .award-row {
    display: flex; justify-content: center; gap: 0; flex-wrap: wrap;
    border-top: 1px solid rgba(74,73,63,0.15); padding-top: 32px;
  }
  .award-item { padding: 0 40px; border-right: 1px solid rgba(74,73,63,0.15); }
  .award-item:last-child { border-right: none; }
  .award-item strong { display: block; font-family: 'Fraunces', serif; font-size: 1.5rem; color: var(--green-mid); margin-bottom: 6px; }
  .award-item span { font-size: 0.72rem; letter-spacing: 0.08em; text-transform: uppercase; color: var(--ink-soft); }

  @media (max-width: 700px) {
    .overview { padding: 70px 6vw 50px; }
    .award-item { padding: 10px 20px; border-right: none; border-bottom: 1px solid rgba(74,73,63,0.15); flex: 1 1 45%; }
    .award-item:last-child { border-bottom: none; }
  }
  @media (max-width: 480px) {
    .award-item { flex: 1 1 100%; text-align: left; padding: 14px 0; }
    .award-row { flex-direction: column; }
  }
</style>
