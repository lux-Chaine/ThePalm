<script>
  import { createEventDispatcher, onMount } from 'svelte';
  import { t } from '../../i18n/index.js';

  const dispatch = createEventDispatcher();

  onMount(() => {
    const revealEls = document.querySelectorAll('.reveal:not(.in)');
    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('in');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });
    revealEls.forEach(el => io.observe(el));
  });
</script>

<section class="hero" id="about">
  <div class="hero-bg">
    <img src="https://the-palm.vercel.app/images/palm1.jpeg" alt="The Palm Hotel exterior">
    <img src="https://the-palm.vercel.app/images/palm2.jpeg" alt="The Palm Hotel exterior">
    <img src="https://the-palm.vercel.app/images/palm3.jpg"  alt="The Palm Hotel exterior">
  </div>
  <svg class="frond-shadow" viewBox="0 0 1200 800" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
    <g fill="#000000">
      <path d="M 900 -50 C 700 40 560 180 480 340 C 620 260 780 210 940 230 C 800 120 900 20 900 -50 Z"/>
      <path d="M 1050 -60 C 830 30 700 190 640 380 C 790 270 970 220 1150 260 C 980 130 1080 30 1050 -60 Z"/>
      <path d="M 1230 -80 C 980 10 830 190 760 400 C 930 270 1130 220 1330 270 C 1140 130 1260 10 1230 -80 Z"/>
    </g>
  </svg>
  <div class="hero-inner">
    <span class="eyebrow reveal in">{$t.hero.eyebrow}</span>
    <h1 class="reveal in">{$t.hero.title}</h1>
    <p class="reveal in">{$t.hero.subtitle}</p>
    <div class="btn-row reveal in">
      <button class="btn-primary" on:click={() => dispatch('open-booking', { roomType: '', guests: '' })}>{$t.hero.cta.primary}</button>
      <a href="#location" class="btn-ghost">{$t.hero.cta.ghost}</a>
    </div>
    <div class="stat-strip reveal in">
      {#each Object.values($t.hero.stats) as stat}
        <div><strong>{stat.value}</strong><span>{stat.label}</span></div>
      {/each}
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
    --paper: #F8F4EA;
    --ink: #201E19;
    --ink-soft: #4A493F;
  }

  .hero {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: flex-end;
    overflow: hidden;
    padding: 0 max(6vw, calc((100vw - 1260px)/2)) 100px;
  }

  .hero-bg { position: absolute; inset: 0; z-index: 0; }

  .hero-bg img {
    position: absolute; inset: 0;
    width: 100%; height: 100%;
    object-fit: cover; opacity: 0;
    animation: heroFade 18s infinite;
  }

  .hero-bg img:nth-child(1) { animation-delay: 0s; }
  .hero-bg img:nth-child(2) { animation-delay: 6s; }
  .hero-bg img:nth-child(3) { animation-delay: 12s; }

  @keyframes heroFade {
    0%   { opacity: 0; }
    5%   { opacity: 1; }
    33%  { opacity: 1; }
    38%  { opacity: 0; }
    100% { opacity: 0; }
  }

  .hero-bg::after {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(180deg, rgba(22,51,42,0.5) 0%, rgba(22,51,42,0.32) 40%, rgba(18,28,23,0.94) 100%);
    z-index: 1;
  }

  @media (prefers-reduced-motion: reduce) {
    .hero-bg img { animation: none; opacity: 1; }
    .hero-bg img:not(:first-child) { display: none; }
  }

  .hero-inner { position: relative; z-index: 3; max-width: 760px; }

  .eyebrow {
    font-family: 'Work Sans', sans-serif;
    font-size: 0.72rem; letter-spacing: 0.22em;
    text-transform: uppercase; font-weight: 600;
    color: var(--gold-light); margin-bottom: 20px; display: block;
  }

  h1 {
    font-family: 'Fraunces', serif; font-weight: 500;
    letter-spacing: -0.01em; line-height: 1.08;
    color: var(--sand); font-size: clamp(2.4rem, 5.6vw, 4.4rem);
    margin-bottom: 22px;
  }

  .hero p {
    color: rgba(241,234,217,0.8); font-size: 1.02rem;
    line-height: 1.7; max-width: 520px; margin-bottom: 34px;
  }

  .btn-row { display: flex; gap: 18px; align-items: center; flex-wrap: wrap; }

  .btn-primary {
    appearance: none;
    border: 1px solid rgba(216, 189, 134, 0.9);
    background: linear-gradient(135deg, #d9bf87 0%, #b8934a 55%, #a77d34 100%);
    color: var(--green-deep);
    padding: 16px 32px;
    font-weight: 700;
    font-size: 0.76rem;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    border-radius: 999px;
    transition: transform 0.25s ease, box-shadow 0.25s ease, filter 0.25s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    box-shadow: 0 12px 28px rgba(184, 147, 74, 0.28);
    cursor: pointer;
  }
  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 18px 30px rgba(184, 147, 74, 0.35);
    filter: brightness(1.04);
  }
  :global([dir='rtl']) .btn-primary { letter-spacing: 0; }

  .btn-ghost {
    color: var(--sand);
    font-size: 0.78rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    border-bottom: 1px solid rgba(241,234,217,0.45);
    padding-bottom: 5px;
    text-decoration: none;
  }

  .stat-strip {
    position: relative; z-index: 3; display: flex; gap: 0;
    margin-top: 56px; border-top: 1px solid rgba(184,147,74,0.3);
    padding-top: 24px; flex-wrap: wrap;
  }
  .stat-strip div { padding-right: 44px; }
  .stat-strip strong { display: block; color: var(--gold-light); font-family: 'Fraunces', serif; font-size: 1.3rem; }
  .stat-strip span { color: rgba(241,234,217,0.6); font-size: 0.75rem; letter-spacing: 0.05em; text-transform: uppercase; }

  .frond-shadow {
    position: absolute; inset: 0; pointer-events: none;
    opacity: 0.22; mix-blend-mode: soft-light; filter: blur(1.5px);
    animation: driftShadow 34s ease-in-out infinite; z-index: 2;
  }
  @keyframes driftShadow {
    0%   { transform: translate(-2%,0%) rotate(-1deg) scale(1); }
    50%  { transform: translate(2.5%,1.5%) rotate(1.2deg) scale(1.04); }
    100% { transform: translate(-2%,0%) rotate(-1deg) scale(1); }
  }
  @media (prefers-reduced-motion: reduce) { .frond-shadow { animation: none; } }

  .reveal { opacity: 0; transform: translateY(24px); transition: opacity .8s ease, transform .8s ease; }
  .reveal.in { opacity: 1; transform: translateY(0); }

  @media (max-width: 860px) {
    .hero { padding: 0 6vw 80px; }
    .hero-inner { max-width: 100%; }
    h1 { font-size: clamp(2rem, 8vw, 3rem); margin-bottom: 18px; }
    .hero p { font-size: 0.95rem; max-width: 100%; }
    .stat-strip { gap: 0; margin-top: 36px; }
    .stat-strip div { padding-right: 24px; margin-bottom: 12px; }
  }
  @media (max-width: 480px) {
    .hero { padding-bottom: 60px; }
    .btn-row { flex-direction: column; align-items: flex-start; gap: 14px; }
    .stat-strip { display: grid; grid-template-columns: 1fr 1fr; gap: 20px 0; }
    .stat-strip div { padding-right: 0; margin-bottom: 0; }
  }
</style>
