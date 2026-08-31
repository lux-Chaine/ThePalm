<script>
  let scrolled = false;
  let menuOpen = false;

  function handleScroll() {
    scrolled = window.scrollY > 40;
  }

  function toggleMenu() {
    menuOpen = !menuOpen;
  }

  function closeMenu() {
    menuOpen = false;
  }

  if (typeof window !== 'undefined') {
    window.addEventListener('scroll', handleScroll);
  }
</script>

<header id="siteHeader" class:scrolled>
  <div class="brand">
    <img src="data:image/jpeg;base64,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" alt="The Palm logo">
    <span>THE PALM</span>
  </div>

  <nav class:open={menuOpen}>
    <ul>
      <li><a href="#about" on:click={closeMenu}>About</a></li>
      <li><a href="#rooms" on:click={closeMenu}>Rooms &amp; Suites</a></li>
      <li><a href="#dining" on:click={closeMenu}>Dining</a></li>
      <li><a href="#meetings" on:click={closeMenu}>Meetings</a></li>
      <li><a href="#offers" on:click={closeMenu}>Offers</a></li>
      <li><a href="#location" on:click={closeMenu}>Location</a></li>
      <li><a href="#reserve" class="nav-cta" on:click={closeMenu}>Reserve</a></li>
    </ul>
  </nav>

  <button class="hamburger" aria-label="Toggle menu" aria-expanded={menuOpen} on:click={toggleMenu}>
    <span class:open={menuOpen}></span>
    <span class:open={menuOpen}></span>
    <span class:open={menuOpen}></span>
  </button>
</header>

<!-- overlay to close menu on outside click -->
{#if menuOpen}
  <div class="menu-overlay" on:click={closeMenu} aria-hidden="true"></div>
{/if}

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

  header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 50;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 26px max(6vw, calc((100vw - 1260px)/2));
    transition: background-color .4s ease, padding .4s ease, box-shadow .4s ease;
  }

  header.scrolled {
    background: rgba(22,51,42,0.94);
    backdrop-filter: blur(6px);
    box-shadow: 0 1px 0 rgba(184,147,74,0.25);
    padding-top: 16px;
    padding-bottom: 16px;
  }

  .brand {
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 52;
  }

  .brand img {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    box-shadow: 0 0 0 1px rgba(255,255,255,0.15);
  }

  .brand span {
    font-family: 'Fraunces', serif;
    font-size: 1rem;
    letter-spacing: 0.02em;
    color: var(--sand);
  }

  /* ── desktop nav ── */
  nav ul {
    list-style: none;
    display: flex;
    gap: 32px;
  }

  nav a {
    color: var(--sand);
    font-size: 0.78rem;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    font-weight: 500;
    position: relative;
    padding-bottom: 4px;
    text-decoration: none;
  }

  nav a::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 0;
    height: 1px;
    background: var(--gold);
    transition: width .3s ease;
  }

  nav a:hover::after {
    width: 100%;
  }

  .nav-cta {
    border: 1px solid var(--gold-light);
    padding: 8px 18px !important;
    border-radius: 2px;
    font-size: 0.74rem !important;
  }

  .nav-cta::after {
    display: none;
  }

  /* ── hamburger button ── */
  .hamburger {
    display: none;
    flex-direction: column;
    justify-content: center;
    gap: 5px;
    width: 36px;
    height: 36px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    z-index: 52;
  }

  .hamburger span {
    display: block;
    width: 24px;
    height: 2px;
    background: var(--sand);
    border-radius: 2px;
    transition: transform 0.3s ease, opacity 0.3s ease;
    transform-origin: center;
  }

  /* X animation */
  .hamburger span:nth-child(1).open {
    transform: translateY(7px) rotate(45deg);
  }
  .hamburger span:nth-child(2).open {
    opacity: 0;
    transform: scaleX(0);
  }
  .hamburger span:nth-child(3).open {
    transform: translateY(-7px) rotate(-45deg);
  }

  /* ── mobile overlay ── */
  .menu-overlay {
    position: fixed;
    inset: 0;
    z-index: 48;
    background: transparent;
  }

  /* ── responsive ── */
  @media (max-width: 860px) {
    .hamburger {
      display: flex;
    }

    nav {
      position: fixed;
      top: 0;
      right: 0;
      bottom: 0;
      width: min(320px, 80vw);
      background: rgba(22,51,42,0.97);
      backdrop-filter: blur(12px);
      z-index: 51;
      transform: translateX(100%);
      transition: transform 0.35s cubic-bezier(.16,1,.3,1);
      padding: 100px 40px 60px;
      box-shadow: -4px 0 40px rgba(0,0,0,0.3);
    }

    nav.open {
      transform: translateX(0);
    }

    nav ul {
      flex-direction: column;
      gap: 0;
    }

    nav ul li {
      border-bottom: 1px solid rgba(184,147,74,0.15);
    }

    nav ul li:first-child {
      border-top: 1px solid rgba(184,147,74,0.15);
    }

    nav a {
      display: block;
      padding: 18px 0;
      font-size: 0.9rem;
      letter-spacing: 0.08em;
    }

    nav a::after {
      display: none;
    }

    .nav-cta {
      display: inline-block;
      margin-top: 28px;
      border: 1px solid var(--gold-light);
      padding: 12px 24px !important;
      font-size: 0.82rem !important;
    }
  }

  @media (max-width: 480px) {
    header {
      padding-left: 5vw;
      padding-right: 5vw;
    }
  }
</style>
