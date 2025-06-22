import './bootstrap.js';
// import './calendar.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    if (calendarEl) {
        const calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, interactionPlugin],
            initialView: 'dayGridMonth',
            selectable: true,
        });

        calendar.render();
    }
});



// FullCalendar
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

import '@fullcalendar/core/index.css';
import '@fullcalendar/daygrid/index.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
