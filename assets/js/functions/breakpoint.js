import vars from '../_vars';

// Like @include min(name) in SCSS (matchMedia measures like CSS): if (minWidth('lg')) { ... }
export const minWidth = (name) => window.matchMedia(`(min-width: ${vars.bp[name]}px)`).matches;
