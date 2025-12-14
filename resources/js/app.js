import './bootstrap'; 
// import 'flatpickr/dist/flatpickr.min.css';
// import "flatpickr/dist/themes/airbnb.css";

// // Import Flatpickr
// import flatpickr from "flatpickr";
// import { Indonesian } from "flatpickr/dist/l10n/id.js";

// flatpickr.localize(Indonesian);

// Import Chart.js
import Chart from 'chart.js/auto';

// Make them globally available
window.flatpickr = flatpickr;
window.Chart = Chart;

// Alpine.js jika perlu
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();