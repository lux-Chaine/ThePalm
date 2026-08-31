import { writable, derived } from 'svelte/store';
import en from './en.js';
import ar from './ar.js';

const translations = { en, ar };

// persist chosen language across page reloads
const stored = (typeof localStorage !== 'undefined' && localStorage.getItem('lang')) || 'en';

export const locale = writable(stored);

// whenever locale changes → persist + flip dir on <html>
locale.subscribe((lang) => {
  if (typeof localStorage !== 'undefined') {
    localStorage.setItem('lang', lang);
  }
  if (typeof document !== 'undefined') {
    document.documentElement.lang = lang;
    document.documentElement.dir = lang === 'ar' ? 'rtl' : 'ltr';
  }
});

// reactive translation object — use $t.hero.title etc.
export const t = derived(locale, ($locale) => translations[$locale] ?? translations.en);

// helper to switch language
export function setLocale(lang) {
  if (translations[lang]) locale.set(lang);
}
