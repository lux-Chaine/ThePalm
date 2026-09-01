<script>
  import { createEventDispatcher } from 'svelte';
  import { t } from '../../i18n/index.js';
  import CountryCodeSelect from './CountryCodeSelect.svelte';

  export let open = false;
  export let roomType = '';
  export let guests = '';

  const dispatch = createEventDispatcher();

  const emptyForm = () => ({
    name: '',
    countryCode: '+20',
    phone: '',
    email: '',
    checkIn: '',
    checkOut: '',
    guests: guests || '2',
    roomType: roomType || 'Standard Room',
    notes: '',
  });

  let form = emptyForm();
  let success = false;
  let error = '';

  function closeModal() {
    dispatch('close');
    success = false;
    error = '';
    form = emptyForm();
  }

  function handleBackdropKeydown(e) {
    if (e.key === 'Escape') {
      closeModal();
    }
  }

  function submitBooking() {
    const fullPhone = form.countryCode + ' ' + form.phone;
    if (!form.name.trim() || !form.phone.trim() || !form.checkIn || !form.checkOut) {
      error = $t.bookingModal.required;
      return;
    }

    // Combine country code and phone number
    form.phone = fullPhone;

    error = '';
    success = true;
  }

  $: if (open) {
    form = emptyForm();
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = '';
  }
</script>

{#if open}
  <div class="modal-backdrop" role="button" tabindex="0" aria-label="Close modal" on:click|self={closeModal} on:keydown={handleBackdropKeydown}>
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="booking-title">
      <button type="button" class="close-btn" aria-label={$t.bookingModal.close} on:click={closeModal}>×</button>

      {#if !success}
        <div class="modal-header">
          <span class="eyebrow">{$t.bookingModal.eyebrow}</span>
          <h3 id="booking-title">{$t.bookingModal.title}</h3>
          <p>{$t.bookingModal.description}</p>
        </div>

        <form class="booking-form" on:submit|preventDefault={submitBooking}>
          <div class="grid two">
            <label>
              <span>{$t.bookingModal.fields.name}</span>
              <input bind:value={form.name} type="text" placeholder={$t.bookingModal.fields.namePlaceholder} />
            </label>

            <label>
              <span>{$t.bookingModal.fields.phone}</span>
              <div class="phone-input-group">
                <CountryCodeSelect bind:countryCode={form.countryCode} />
                <input bind:value={form.phone} type="tel" placeholder={$t.bookingModal.fields.phonePlaceholder} />
              </div>
            </label>
          </div>

          <div class="grid two">
            <label>
              <span>{$t.bookingModal.fields.email}</span>
              <input bind:value={form.email} type="email" placeholder={$t.bookingModal.fields.emailPlaceholder} />
            </label>

            <label>
              <span>{$t.bookingModal.fields.guests}</span>
              <select bind:value={form.guests}>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
              </select>
            </label>
          </div>

          <div class="grid two">
            <label>
              <span>{$t.bookingModal.fields.checkIn}</span>
              <input bind:value={form.checkIn} type="date" />
            </label>

            <label>
              <span>{$t.bookingModal.fields.checkOut}</span>
              <input bind:value={form.checkOut} type="date" />
            </label>
          </div>

          <label>
            <span>{$t.bookingModal.fields.roomType}</span>
            <select bind:value={form.roomType}>
              <option value="Standard Room">{$t.bookingModal.roomOptions.standard}</option>
              <option value="Superior Room">{$t.bookingModal.roomOptions.superior}</option>
              <option value="Junior Suite">{$t.bookingModal.roomOptions.junior}</option>
              <option value="Executive Suite">{$t.bookingModal.roomOptions.executive}</option>
            </select>
          </label>

          <label>
            <span>{$t.bookingModal.fields.notes}</span>
            <textarea bind:value={form.notes} rows="4" placeholder={$t.bookingModal.fields.notesPlaceholder}></textarea>
          </label>

          {#if error}
            <p class="error">{error}</p>
          {/if}

          <div class="actions">
            <button type="button" class="secondary-btn" on:click={closeModal}>{$t.bookingModal.cancel}</button>
            <button type="submit" class="primary-btn">{$t.bookingModal.submit}</button>
          </div>
        </form>
      {:else}
        <div class="success-box">
          <div class="success-icon">✓</div>
          <h3>{$t.bookingModal.successTitle}</h3>
          <p>{$t.bookingModal.successMessage}</p>
          <button type="button" class="primary-btn" on:click={closeModal}>{$t.bookingModal.close}</button>
        </div>
      {/if}
    </div>
  </div>
{/if}

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
    --danger: #b33a3a;
  }

  .modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(9, 17, 15, 0.68);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 24px;
    backdrop-filter: blur(4px);
  }

  .modal {
    position: relative;
    width: min(760px, 100%);
    background: linear-gradient(180deg, #fffdf9 0%, #f8f2e7 100%);
    border: 1px solid rgba(22, 51, 42, 0.08);
    border-radius: 24px;
    box-shadow: 0 30px 80px rgba(15, 37, 30, 0.28);
    padding: 32px 28px 24px;
    max-height: 92vh;
    overflow-y: auto;
  }

  .close-btn {
    position: absolute;
    top: 18px;
    right: 18px;
    width: 38px;
    height: 38px;
    border: 1px solid rgba(22, 51, 42, 0.15);
    border-radius: 50%;
    background: rgba(22, 51, 42, 0.06);
    color: var(--green-deep);
    font-size: 2rem;
    line-height: 1;
    cursor: pointer;
    transition: background 0.25s ease, transform 0.25s ease, border-color 0.25s ease;
  }

  .close-btn:hover {
    background: rgba(184, 147, 74, 0.15);
    border-color: rgba(184, 147, 74, 0.4);
    transform: rotate(90deg);
  }

  .modal-header {
    margin-bottom: 20px;
    padding-right: 36px;
  }

  .eyebrow {
    display: inline-block;
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.18em;
    color: var(--gold);
    font-weight: 700;
    margin-bottom: 10px;
  }

  h3 {
    font-family: 'Fraunces', serif;
    color: var(--green-deep);
    font-size: clamp(1.8rem, 3vw, 2.5rem);
    line-height: 1.1;
    margin: 0 0 8px;
  }

  .modal-header p {
    color: var(--ink-soft);
    margin: 0;
    line-height: 1.7;
  }

  .booking-form {
    display: grid;
    gap: 18px;
  }

  .grid {
    display: grid;
    gap: 18px;
  }

  .grid.two {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  label {
    display: grid;
    gap: 8px;
    color: var(--green-deep);
    font-weight: 600;
    font-size: 0.92rem;
  }

  input,
  select,
  textarea {
    width: 100%;
    border: 1px solid rgba(22, 51, 42, 0.18);
    border-radius: 10px;
    background: #fff;
    padding: 12px 14px;
    color: var(--ink);
    font: inherit;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }

  input:focus,
  select:focus,
  textarea:focus {
    outline: none;
    border-color: rgba(184, 147, 74, 0.9);
    box-shadow: 0 0 0 4px rgba(184, 147, 74, 0.15);
  }

  textarea {
    resize: vertical;
    min-height: 110px;
  }

  .phone-input-group {
    display: flex;
    gap: 8px;
  }

  :global([dir='rtl']) .phone-input-group {
    flex-direction: row-reverse;
  }

  .phone-input-group input {
    flex: 1;
  }

  .error {
    margin: 0;
    color: var(--danger);
    font-size: 0.9rem;
    font-weight: 600;
  }

  .actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 6px;
  }

  .primary-btn,
  .secondary-btn {
    appearance: none;
    border: 1px solid rgba(216, 189, 134, 0.9);
    border-radius: 999px;
    padding: 16px 32px;
    font-weight: 700;
    font-size: 0.76rem;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    cursor: pointer;
    transition: transform 0.25s ease, box-shadow 0.25s ease, filter 0.25s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
  }

  .primary-btn {
    background: linear-gradient(135deg, #d9bf87 0%, #b8934a 55%, #a77d34 100%);
    color: var(--green-deep);
    box-shadow: 0 12px 28px rgba(184, 147, 74, 0.28);
  }

  .primary-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 18px 30px rgba(184, 147, 74, 0.35);
    filter: brightness(1.04);
  }

  .secondary-btn {
    background: rgba(22, 51, 42, 0.06);
    color: var(--green-deep);
    border: 1px solid rgba(22, 51, 42, 0.15);
    box-shadow: none;
  }

  .secondary-btn:hover {
    transform: translateY(-2px);
    background: rgba(22, 51, 42, 0.1);
  }

  :global([dir='rtl']) .primary-btn,
  :global([dir='rtl']) .secondary-btn {
    letter-spacing: 0;
  }

  .success-box {
    text-align: center;
    padding: 24px 12px 12px;
  }

  .success-icon {
    width: 74px;
    height: 74px;
    margin: 0 auto 18px;
    display: grid;
    place-items: center;
    border-radius: 50%;
    background: rgba(22, 51, 42, 0.08);
    color: var(--green-deep);
    font-size: 2rem;
    font-weight: 700;
  }

  .success-box p {
    color: var(--ink-soft);
    line-height: 1.7;
    margin: 0 0 20px;
  }

  @media (max-width: 640px) {
    .modal {
      padding: 28px 18px 18px;
    }

    .grid.two {
      grid-template-columns: 1fr;
    }

    .phone-input-group {
      flex-direction: column;
    }

    :global([dir='rtl']) .phone-input-group {
      flex-direction: column;
    }

    .actions {
      flex-direction: column-reverse;
    }

    .primary-btn,
    .secondary-btn {
      width: 100%;
      padding: 14px 24px;
    }
  }

  /* Custom scrollbar for modal */
  .modal::-webkit-scrollbar {
    width: 2px;
  }

  .modal::-webkit-scrollbar-track {
    background: transparent;
  }

  .modal::-webkit-scrollbar-thumb {
    background: rgba(184, 147, 74, 0.5);
    border-radius: 1px;
  }

  .modal::-webkit-scrollbar-thumb:hover {
    background: rgba(184, 147, 74, 0.8);
  }

  .modal {
    scrollbar-width: none;
  }
</style>
