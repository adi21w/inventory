document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            timeZone: 'Asia/Jakarta',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,listMonth' // 🔥 Ini tombol view
            },
            views: {
                listMonth: {
                    buttonText: 'Agenda'
                },
                dayGridMonth: {
                    buttonText: 'Bulanan'
                }
            },
            events: [{
                    title: 'Meeting Bos',
                    start: '2025-07-01'
                },
                {
                    title: 'Deadline Project',
                    start: '2025-07-05',
                    end: '2025-07-07'
                }
            ],
            dayCellDidMount: function(info) {
                const day = info.date.getDay(); // 0 = Minggu, 6 = Sabtu
                if (day === 0 || day === 6) {
                    info.el.style.backgroundColor = '#ffe6e6'; // merah muda = hari libur
                    info.el.style.opacity = '0.7';
                }
            },
            // events: $helper.path.create('/api/kalendar-event'),
            // eventColor: '#000',
            eventClick: function(info) {
                let tglIndo = $helper.dateFormat.toID(info.event.start)
                Swal.fire({
                    icon : 'info',
                    title : info.event.title,
                    text : info.event.extendedProps.keterangan+' / '+tglIndo,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                })
            },
            loading: function (isLoading) {
                if (isLoading) {
                    $('.disabled-load').addClass('show')
                    // Swal.fire({
                    //     icon : 'info',
                    //     title : 'Sedang memuat data',
                    //     text : 'Loading...',
                    //     allowOutsideClick: false,
                    //     allowEscapeKey: false,
                    //     showConfirmButton: false
                    // })
                } else {
                    $('.disabled-load').removeClass('show')
                    // Swal.close()
                }
            },
        });

        calendar.render();
    });