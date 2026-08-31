<script>
  import { reveal } from '../../actions/reveal.js';
  import { t } from '../../i18n/index.js';
</script>

<section class="cta-banner" id="reserve">
  <span class="eyebrow" use:reveal>{$t.cta.eyebrow}</span>
  <h2 use:reveal={{ delay: 100 }}>{$t.cta.title}</h2>
  <div class="contact-row" use:reveal={{ delay: 200 }}>
    <a href="mailto:{$t.cta.contact.email}">{$t.cta.contact.email}</a>
    <a href="tel:+201103145834">{$t.cta.contact.phone}</a>
    <a href="https://wa.me/201103145834">{$t.cta.contact.whatsapp}</a>
  </div>
  <a href="mailto:{$t.cta.contact.email}" class="btn-primary" use:reveal={{ delay: 300 }}>{$t.cta.button}</a>
</section>

<style>
  :root {
    --green-deep: #16332A; --gold: #B8934A; --gold-light: #D8BD86;
    --sand: #F1EAD9; --paper: #F8F4EA; --ink: #201E19;
  }
  .cta-banner {
    position: relative; background: var(--green-deep);
    padding: 130px max(6vw, calc((100vw - 1260px)/2));
    text-align: center; overflow: hidden;
  }
  .eyebrow { font-family: 'Work Sans', sans-serif; font-size: 0.72rem; letter-spacing: 0.22em; text-transform: uppercase; font-weight: 600; color: var(--gold-light); margin-bottom: 22px; display: block; }
  h2 { position: relative; z-index: 2; color: var(--sand); font-family: 'Fraunces', serif; font-weight: 500; letter-spacing: -0.01em; line-height: 1.08; font-size: clamp(2rem,4.2vw,3.3rem); max-width: 800px; margin: 0 auto 36px; }
  .contact-row { position: relative; z-index: 2; display: flex; justify-content: center; gap: 40px; flex-wrap: wrap; margin-bottom: 40px; font-size: 0.95rem; }
  .contact-row a { color: rgba(241,234,217,0.75); text-decoration: none; border-bottom: 1px solid rgba(184,147,74,0.4); padding-bottom: 2px; transition: color 0.2s ease, border-color 0.2s ease; }
  .contact-row a:hover { color: var(--gold-light); border-color: var(--gold-light); }
  .btn-primary { position: relative; z-index: 2; background: var(--gold); color: var(--green-deep); padding: 15px 30px; font-weight: 600; font-size: 0.82rem; letter-spacing: 0.04em; text-transform: uppercase; border-radius: 2px; transition: transform .25s ease, background .25s ease; display: inline-block; text-decoration: none; }
  .btn-primary:hover { transform: translateY(-2px); background: var(--gold-light); }

  @media (max-width: 900px) { .cta-banner { padding: 80px 6vw; } }
  @media (max-width: 540px) {
    .cta-banner { padding: 70px 6vw; }
    h2 { font-size: clamp(1.7rem, 7vw, 2.4rem); margin-bottom: 28px; }
    .contact-row { flex-direction: column; gap: 16px; align-items: center; }
  }
</style>
