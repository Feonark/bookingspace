document.addEventListener('DOMContentLoaded', function () {

  const calendarEl = document.getElementById('calendar');

  if (calendarEl) {
    const roomId = calendarEl.dataset.roomId;
    if (!roomId) {
      console.error('Room ID is missing on #calendar element');
      return;
    }

    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale: 'fr',
      editable: false,
      eventClick: function (info) {
        alert('Réservation sélectionnée : ' + info.event.title +
          '\nDébut : ' + info.event.start.toLocaleString() +
          '\nFin : ' + (info.event.end ? info.event.end.toLocaleString() : ''));
        info.jsEvent.preventDefault();
      },
      dateClick: function (info) {
        document.querySelectorAll('.fc-daygrid-day.selected-day').forEach(el => {
          el.classList.remove('bg-blue-500');
        });

        info.dayEl.classList.add('bg-blue-500');

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
