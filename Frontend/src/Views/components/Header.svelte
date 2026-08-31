<script>
  import { t, locale, setLocale } from '../../i18n/index.js';

  let scrolled = false;
  let menuOpen = false;

  function handleScroll() {
    scrolled = window.scrollY > 40;
  }

  function toggleMenu() { menuOpen = !menuOpen; }
  function closeMenu()  { menuOpen = false; }
  function toggleLang() { setLocale($locale === 'en' ? 'ar' : 'en'); menuOpen = false; }

  function scrollTo(id) {
    menuOpen = false;
    const el = document.getElementById(id);
    if (el) el.scrollIntoView({ behavior: 'smooth' });
  }

  function handleKeydown(e) {
    if (e.key === 'Escape' && menuOpen) {
      closeMenu();
    }
  }

  $: if (typeof document !== 'undefined') {
    if (menuOpen) {
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = '';
    }
  }

  if (typeof window !== 'undefined') {
    window.addEventListener('scroll', handleScroll);
  }
</script>

<svelte:window on:keydown={handleKeydown} />

<header class:scrolled class:menu-open={menuOpen}>
  <div class="brand" on:click={() => scrollTo('top')} role="button" tabindex="0" on:keydown={(e) => e.key === 'Enter' && scrollTo('top')}>
    <img src="/images/Logo.png" alt="The Palm Hotel logo">
    <span>{$t.header.brand}</span>
  </div>

  <nav class="nav-drawer" class:open={menuOpen} aria-label="Main Navigation">
    <div class="drawer-header">
      <div class="drawer-brand">
        <span>{$t.header.brand}</span>
      </div>
      <button class="drawer-close" on:click={closeMenu} aria-label="Close menu">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
    </div>

    <ul class="nav-list">
      <li><button class="nav-link" on:click={() => scrollTo('about')}>{$t.header.nav.about}</button></li>
      <li><button class="nav-link" on:click={() => scrollTo('location')}>{$t.header.nav.location}</button></li>
      <li><button class="nav-link" on:click={() => scrollTo('rooms')}>{$t.header.nav.rooms}</button></li>
      <li><button class="nav-link" on:click={() => scrollTo('dining')}>{$t.header.nav.dining}</button></li>
      <li><button class="nav-link" on:click={() => scrollTo('meetings')}>{$t.header.nav.meetings}</button></li>
      <li><button class="nav-link" on:click={() => scrollTo('offers')}>{$t.header.nav.offers}</button></li>
      <li><button class="nav-link nav-cta" on:click={() => scrollTo('reserve')}>{$t.header.nav.reserve}</button></li>
      <li class="mobile-lang">
        <button on:click={() => { toggleLang(); }}>
          {$locale === 'en' ? 'العربية' : 'English'}
        </button>
      </li>
    </ul>
  </nav>

  <div class="actions">
    <button class="lang-btn" on:click={toggleLang}>
      {$locale === 'en' ? 'ع' : 'EN'}
    </button>
    <button class="burger" aria-label="Menu" on:click={toggleMenu} aria-expanded={menuOpen}>
      <span class:x={menuOpen}></span>
      <span class:x={menuOpen}></span>
      <span class:x={menuOpen}></span>
    </button>
  </div>
</header>

{#if menuOpen}
  <div class="overlay" on:click={closeMenu} aria-hidden="true"></div>
{/if}

<style>
  :root {
    --green-deep: #16332A;
    --green-darker: #0f251e;
    --gold: #B8934A;
    --gold-light: #D8BD86;
    --sand: #F1EAD9;
  }

  header {
    position: fixed;
    top: 0; left: 0; right: 0;
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 24px max(6vw, calc((100vw - 1260px) / 2));
    transition: background .4s ease, padding .4s ease, box-shadow .4s ease;
  }

  header.menu-open {
    z-index: 1000;
  }

  header.scrolled {
    background: rgba(22, 51, 42, 0.95);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    box-shadow: 0 1px 0 rgba(184, 147, 74, 0.25);
    padding-top: 14px;
    padding-bottom: 14px;
  }

  /* brand */
  .brand {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
    cursor: pointer;
    user-select: none;
  }

  .brand img {
    height: 65px;
    width: auto;
    max-width: 140px;
    object-fit: contain;
    transition: height .3s ease;
  }

  header.scrolled .brand img {
    height: 75px;
  }

  .brand span {
    font-family: 'Fraunces', serif;
    font-size: 1rem;
    color: var(--sand);
    white-space: nowrap;
    letter-spacing: 0.02em;
  }

  .drawer-header {
    display: none;
  }

  /* nav */
  .nav-list {
    list-style: none;
    display: flex;
    align-items: center;
    gap: 26px;
    margin: 0;
    padding: 0;
  }

  .nav-link {
    color: var(--sand);
    font-size: 0.78rem;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    font-weight: 500;
    text-decoration: none;
    white-space: nowrap;
    position: relative;
    padding-bottom: 3px;
    background: none;
    border: none;
    cursor: pointer;
    font-family: 'Work Sans', sans-serif;
    transition: color .25s ease;
  }

  .nav-link::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 0;
    height: 1px;
    background: var(--gold);
    transition: width .3s ease;
  }

  .nav-link:hover {
    color: var(--gold-light);
  }

  .nav-link:hover::after {
    width: 100%;
  }

  .nav-cta {
    border: 1px solid var(--gold-light);
    padding: 7px 14px;
    border-radius: 2px;
    color: var(--gold-light);
  }
  .nav-cta:hover {
    background: rgba(184, 147, 74, 0.15);
  }
  .nav-cta::after {
    display: none;
  }

  /* RTL nav — readable Arabic size */
  :global([dir='rtl']) .nav-link {
    text-transform: none;
    letter-spacing: 0;
    font-size: 0.9rem;
  }
  :global([dir='rtl']) .nav-list {
    gap: 18px;
  }

  /* actions */
  .actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
    z-index: 52;
  }

  .lang-btn {
    background: none;
    border: 1px solid rgba(184,147,74,0.5);
    color: var(--gold-light);
    font-family: 'Work Sans', sans-serif;
    font-size: 0.78rem;
    font-weight: 600;
    padding: 5px 11px;
    border-radius: 2px;
    cursor: pointer;
    transition: background .2s, border-color .2s;
    white-space: nowrap;
  }
  .lang-btn:hover {
    background: rgba(184,147,74,0.15);
    border-color: var(--gold);
  }

  /* burger — hidden on desktop */
  .burger {
    display: none;
    flex-direction: column;
    gap: 5px;
    width: 36px;
    height: 36px;
    justify-content: center;
    align-items: center;
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    z-index: 60;
  }

  .burger span {
    display: block;
    width: 22px;
    height: 2px;
    background: var(--sand);
    border-radius: 2px;
    transition: transform .3s ease, opacity .3s ease, background-color .3s ease;
    transform-origin: center;
  }
  .burger span:nth-child(1).x { transform: translateY(7px) rotate(45deg); }
  .burger span:nth-child(2).x { opacity: 0; transform: scaleX(0); }
  .burger span:nth-child(3).x { transform: translateY(-7px) rotate(-45deg); }

  /* mobile lang item — hidden on desktop */
  .mobile-lang {
    display: none;
  }

  .overlay {
    position: fixed;
    inset: 0;
    z-index: 900;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    animation: fadeIn 0.25s ease-out;
  }

  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  /* ── Mobile Side Drawer ── */
  @media (max-width: 900px) {
    .burger {
      display: flex;
    }
    .lang-btn {
      display: none;
    }

    .nav-drawer {
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      right: auto;
      width: min(320px, 85vw);
      height: 100vh;
      height: 100dvh;
      background: #143027;
      background: linear-gradient(180deg, #183b30 0%, #112921 100%);
      z-index: 1001;
      box-shadow: 8px 0 32px rgba(0, 0, 0, 0.55);
      transform: translateX(-100%);
      transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
      padding: 24px 24px 36px;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
      box-sizing: border-box;
    }

    :global([dir='rtl']) .nav-drawer {
      left: auto;
      right: 0;
      transform: translateX(100%);
      box-shadow: -8px 0 32px rgba(0, 0, 0, 0.55);
    }

    .nav-drawer.open {
      transform: translateX(0) !important;
    }

    .drawer-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-bottom: 18px;
      margin-bottom: 14px;
      border-bottom: 1px solid rgba(216, 189, 134, 0.25);
    }

    .drawer-brand span {
      font-family: 'Fraunces', serif;
      font-size: 1.2rem;
      color: #FAF6EE;
      font-weight: 500;
      letter-spacing: 0.04em;
    }

    .drawer-close {
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(216, 189, 134, 0.35);
      color: #FAF6EE;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: background 0.2s, border-color 0.2s, color 0.2s;
    }

    .drawer-close:hover {
      background: rgba(216, 189, 134, 0.25);
      border-color: #D8BD86;
      color: #D8BD86;
    }

    .nav-list {
      flex-direction: column;
      align-items: stretch;
      gap: 0;
      width: 100%;
    }

    .nav-list li {
      width: 100%;
      border-bottom: 1px solid rgba(216, 189, 134, 0.15);
    }

    .nav-link {
      display: block;
      width: 100%;
      padding: 14px 10px;
      font-size: 0.95rem;
      font-weight: 500;
      letter-spacing: 0.04em;
      text-align: start;
      color: #FAF6EE !important;
      border-radius: 4px;
      transition: background 0.2s ease, color 0.2s ease;
    }

    :global([dir='rtl']) .nav-link {
      font-size: 1rem;
      letter-spacing: 0;
      text-align: right;
    }

    .nav-link:hover,
    .nav-link:active {
      background: rgba(216, 189, 134, 0.12);
      color: #D8BD86 !important;
    }

    .nav-link::after {
      display: none;
    }

    .nav-cta {
      border: 1px solid rgba(216, 189, 134, 0.6) !important;
      background: rgba(216, 189, 134, 0.12) !important;
      padding: 12px 14px !important;
      color: #D8BD86 !important;
      font-weight: 600;
      text-align: center !important;
      margin-top: 12px;
    }

    .nav-cta:hover,
    .nav-cta:active {
      background: rgba(216, 189, 134, 0.25) !important;
      border-color: #D8BD86 !important;
      color: #FAF6EE !important;
    }

    .mobile-lang {
      display: block;
      border: none !important;
      padding-top: 22px;
      margin-top: auto;
    }

    .mobile-lang button {
      background: rgba(216, 189, 134, 0.1);
      border: 1px solid rgba(216, 189, 134, 0.4);
      color: #FAF6EE;
      font-family: 'Work Sans', sans-serif;
      font-size: 0.92rem;
      font-weight: 600;
      padding: 12px 20px;
      border-radius: 4px;
      cursor: pointer;
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: background 0.2s, border-color 0.2s, color 0.2s;
    }

    .mobile-lang button:hover {
      background: rgba(216, 189, 134, 0.22);
      border-color: #D8BD86;
      color: #D8BD86;
    }
  }

  @media (max-width: 480px) {
    header {
      padding-left: 5vw;
      padding-right: 5vw;
    }
    .nav-drawer {
      width: min(300px, 88vw);
      padding: 20px 20px 30px;
    }
  }
</style>
