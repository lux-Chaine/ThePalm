<script>
  import { t } from '../../i18n/index.js';
</script>

<footer>
  <div class="footer-grid">
    <div>
      <div class="footer-brand">
        <img src="/images/Logo.png" alt="The Palm logo">
        <span>{$t.footer.brand}</span>
      </div>
      <p class="tag">{$t.footer.tagline}</p>
    </div>
    <div class="footer-col">
      <h4>{$t.footer.explore.heading}</h4>
      {#each $t.footer.explore.links as link}
        <a href={link.href}>{link.label}</a>
      {/each}
    </div>
    <div class="footer-col">
      <h4>{$t.footer.reservations.heading}</h4>
      <a href="mailto:{$t.footer.reservations.email}">{$t.footer.reservations.email}</a>
      <a href="tel:{$t.footer.reservations.phone}">{$t.footer.reservations.phone}</a>
      <a href="https://www.bing.com/maps/search?q=The+Palm+Hotel+Kafr+El+Sheikh" target="_blank" rel="noopener noreferrer">{$t.footer.reservations.address}</a>
    </div>
    <div class="footer-col">
      <h4>{$t.footer.follow.heading}</h4>
      <a href="https://www.facebook.com/share/1SaDsaHpfk/" target="_blank" rel="noopener noreferrer">{$t.footer.follow.facebook}</a>
      <a href="https://www.instagram.com/thepalmhotel.eg" target="_blank" rel="noopener noreferrer">{$t.footer.follow.instagram}</a>
    </div>
  </div>
  <div class="footer-bottom">
    <span>{$t.footer.bottom.copyright}</span>
    <span>{$t.footer.bottom.location}</span>
  </div>
</footer>

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

  footer {
    background: var(--sand-warm);
    padding: 64px max(6vw, calc((100vw - 1260px)/2)) 40px;
  }

  .footer-grid {
    display: grid;
    grid-template-columns: 1.4fr 1fr 1fr 1fr;
    gap: 40px;
    margin-bottom: 50px;
  }

  .footer-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
  }

  .footer-brand img {
    height: 75px;
    width: auto;
    max-width: 150px;
    object-fit: contain;
  }

  .footer-brand span {
    font-family: 'Fraunces', serif;
    font-size: 1.1rem;
    color: var(--ink);
  }

  footer p.tag {
    color: var(--ink-soft);
    font-size: 0.9rem;
    max-width: 280px;
    line-height: 1.6;
  }

  .footer-col h4 {
    font-size: 0.75rem;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--ink-soft);
    margin-bottom: 18px;
    font-weight: 600;
  }

  .footer-col a {
    display: block;
    font-size: 0.92rem;
    color: var(--ink);
    margin-bottom: 12px;
    text-decoration: none;
    transition: color 0.2s ease;
  }

  .footer-col a:hover {
    color: var(--gold);
  }

  .footer-bottom {
    border-top: 1px solid rgba(74,73,63,0.2);
    padding-top: 24px;
    display: flex;
    justify-content: space-between;
    font-size: 0.78rem;
    color: var(--ink-soft);
    flex-wrap: wrap;
    gap: 10px;
  }

  @media (max-width: 900px) {
    .footer-grid {
      grid-template-columns: 1fr 1fr;
      gap: 36px;
    }
  }

  @media (max-width: 540px) {
    footer {
      padding: 50px 6vw 32px;
    }

    .footer-grid {
      grid-template-columns: 1fr;
      gap: 32px;
    }

    footer p.tag {
      max-width: 100%;
    }

    .footer-bottom {
      flex-direction: column;
      gap: 6px;
      text-align: center;
    }
  }
</style>
