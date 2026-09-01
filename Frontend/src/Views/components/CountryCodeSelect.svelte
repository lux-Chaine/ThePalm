<script>
  import { onMount } from 'svelte';
  import { getCountries, getCountryCallingCode } from 'libphonenumber-js';

  export let countryCode = '+20';

  let countries = [];

  onMount(() => {
    countries = getCountries().map(country => {
      try {
        const code = getCountryCallingCode(country);
        return {
          code: country,
          callingCode: '+' + code,
          name: country
        };
      } catch (e) {
        return null;
      }
    }).filter(Boolean);

    // Sort countries and prioritize common ones
    const priorityCountries = ['EG', 'SA', 'AE', 'QA', 'KW', 'BH', 'OM', 'US', 'GB', 'DE', 'FR', 'IT'];
    countries.sort((a, b) => {
      const aIndex = priorityCountries.indexOf(a.code);
      const bIndex = priorityCountries.indexOf(b.code);
      if (aIndex !== -1 && bIndex !== -1) return aIndex - bIndex;
      if (aIndex !== -1) return -1;
      if (bIndex !== -1) return 1;
      return a.code.localeCompare(b.code);
    });
  });

  function handleCountryChange(event) {
    countryCode = event.target.value;
  }
</script>

<div class="country-select-wrapper">
  <select 
    bind:value={countryCode} 
    class="country-select"
    on:change={handleCountryChange}
  >
    {#each countries as country}
      <option value={country.callingCode}>
        {country.callingCode} - {country.name}
      </option>
    {/each}
  </select>
</div>

<style>
  .country-select-wrapper {
    position: relative;
  }

  .country-select {
    appearance: none;
    width: 100%;
    min-width: 120px;
    padding: 12px 14px;
    border: 1px solid rgba(22, 51, 42, 0.18);
    border-radius: 10px;
    background: #fff;
    color: var(--ink, #201E19);
    font: inherit;
    cursor: pointer;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%232014019' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    padding-right: 30px;
  }

  .country-select:focus {
    outline: none;
    border-color: rgba(184, 147, 74, 0.9);
    box-shadow: 0 0 0 4px rgba(184, 147, 74, 0.15);
  }

  :global([dir='rtl']) .country-select {
    background-position: left 14px center;
    padding-right: 14px;
    padding-left: 30px;
  }

  @media (max-width: 640px) {
    .country-select {
      width: 100%;
    }
  }
</style>
