const baseCU = '/produk'
document.addEventListener("DOMContentLoaded", function(event) {
    $(document).on('click', '.btn-aktif-produk', function(e){
        var button = $(this)
        button.buttonFn('loading')

        let pabID = $(this).attr('data-id')
        let pabSts = $(this).attr('data-active')
        let messag = pabSts == 'Aktif' ? 'Aktifkan' : 'Menonaktifkan'

        Swal.fire({
            title: "Are you sure?",
            text: `${messag} Produk ini?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#59adff",
            cancelButtonColor: "#ff4557",
            confirmButtonText: "Yes, run it!"
        }).then((result) => {
            if (result.isConfirmed) {
                let params = ajaxParams(pathON.createUrl(`${baseCU}/status`), 'GET', {id: pabID, status: pabSts})
                $.ajax(params).done(function(response) {
                    if (response.status) {
                        $.pjax.reload({container: '#produkGrid0', async : false})
                        calert.success(response.message)
                    } else {
                        calert.error(response.error)
                    }
                })
            }
            button.buttonFn('reset')
        });
    })

    $('#button-generate').click(function(e) {
        let params = {}
        let vendor = $('#produk-ivendorid').val()
        let kategori = $('#produk-ikategoriid').val()
        let company = $('#produk-ecompany').val()

        if (vendor) {
            params.vendor = vendor
        }
        if (kategori) {
            params.kategori = kategori
        }
        if (company) {
            params.company = company
        }

        let urlPath = pathON.createUrl(`${baseCU}/report`, params)
        window.open(urlPath)

    })
})