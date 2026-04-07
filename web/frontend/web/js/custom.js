//function untuk memberi css active ke menu
function showActiveMenu() {
    $('.sidebar-item').removeClass('active')
    let route = $helper.parsingURL()
    let baseRoute = ['/', '/#', '/site', '/site/']
    let header = $('#sidebar').find('.sidebar-menu').eq(0)
    let findChild = header.find('[data-route]')

    if (baseRoute.includes(route)) {
        return findChild.eq(0).addClass('active')
    } else {
        let parentRoute = $('[data-route-parent]').length
        if (parentRoute > 0) {
            route = $('[data-route-parent]').eq(0).attr('data-route-parent')
        }
        let parseSplit = route.split('/')
        if (parseSplit.length == 2) {
            route += '/index'
        }

        findChild.each(function(i){
            let child = $(this).find('a').attr('href')
            let newRoute = $helper.path.base + route
            if (child == newRoute) {
                let checkNotSub = $(this).hasClass('sidebar-item')
                if (checkNotSub) {
                    return $(this).addClass('active')
                } else {
                    $(this).closest('.sidebar-item.has-sub').addClass('active')
                    $(this).closest('.submenu-item').addClass('active')
                    $(this).closest('.submenu').addClass('active').show()
                    return
                }
            }
        })
    }
}

$.fn.extend({
    buttonFn: function(action) {
        if (action === 'loading') {
            this.data('original-text', this.html());
            this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
        } else if (action === 'reset') {
            this.prop('disabled', false).html(this.data('original-text'));
        }
        return this;
    }
});

document.addEventListener("DOMContentLoaded", function(event) {
    showActiveMenu()

    $(document).on('click', '.btn-copy', function () {
        const teks = $(this).attr('title');

        // Pakai clipboard modern
        navigator.clipboard.writeText(teks).then(() => {
            // Optional: feedback visual
            console.log(teks)
            Toastify({
                text: "Kode berhasil dicopy",
                duration: 3000,
                close:true,
                gravity:"top",
                position: "center",
            }).showToast();
        }).catch(err => {
            console.error('Gagal menyalin:', err);
        });
    });

    $(document).on('click', '[data-button-confirm]', function(e) {
        e.preventDefault(); // Stop form biar gak langsung lari
        var form = $(this).closest('form');

        Swal.fire({
            title: 'Konfirmasi Data berikut',
            text: "Pastikan data sudah sesuai!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Kirim form secara manual jika OK
            }
        });
    });

})