// Webpack entry. Components: always loaded. Modules: separate files, loaded only on pages with their selector
import {loadModules} from './functions/load-modules';

import './components/_custom';
import './components/adminbar';
import './components/header-scrolled';
import './components/navigation';
import './components/to-top';
import './components/accordion';
import './components/modal';
import './components/tabs';
import './components/toggle';
import './components/cf7-events'; // Custom Contact Form 7 scripts

loadModules({
	'.swiper': () => import(/* webpackChunkName: "slider" */ './modules/slider'),
	'[data-lightbox]': () => import(/* webpackChunkName: "lightbox" */ './modules/lightbox'),
	'.m-hero': () => import(/* webpackChunkName: "hero" */ './modules/hero'),
	'.m-partnership': () => import(/* webpackChunkName: "partnership" */ './modules/partnership'),
});
