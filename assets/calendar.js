import {Calendar} from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    if (calendarEl) {
        const roomId = calendarEl.dataset.roomId;
        if (!roomId) {
            console.error('Room ID is missing on #calendar element');
            return;
        }

        const calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
            initialView: 'dayGridMonth',
            locale: 'fr',
            editable: false,
            eventClick: function (info) {
                // Exemple simple : afficher une alerte avec les infos de l'événement
                alert('Réservation sélectionnée : ' + info.event.title +
                    '\nDébut : ' + info.event.start.toLocaleString() +
                    '\nFin : ' + info.event.end?.toLocaleString());

                // Optionnel : empêcher que le navigateur suive un lien
                info.jsEvent.preventDefault();
            },
            dateClick: function (info) {
                document.querySelectorAll('.fc-daygrid-day.selected-day').forEach(el => {
                    el.classList.remove('bg-blue-500');
                });

                // Ajouter une classe au jour cliqué
                info.dayEl.classList.add('bg-blue-500');

                const clickedDate = info.dateStr;
                if (!document.getElementById('booking_form_dateStart').value) {
                    document.getElementById('booking_form_dateStart').value = info.dateStr;
                } else {
                    if (document.getElementById('booking_form_dateStart').value > info.dateStr) {
                        document.getElementById('booking_form_dateEnd').value = document.getElementById('booking_form_dateStart').value;
                        document.getElementById('booking_form_dateStart').value = info.dateStr;
                    } else {
                        document.getElementById('booking_form_dateEnd').value = info.dateStr;
                    }
                }
            },

            events: `/eventroom/${roomId}/bookings`,
        });

        calendar.render();
    }
});
