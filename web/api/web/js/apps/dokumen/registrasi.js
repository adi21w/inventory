const baseCU = '/dokumen'
document.addEventListener("DOMContentLoaded", function(event) {
    $(document).on('click', '.btn-aktif-registrasi', function(e){
        var button = $(this)
        button.buttonFn('loading')

        let pabID = $(this).attr('data-id')
        let pabSts = $(this).attr('data-active')
        let messag = pabSts == 'Aktif' ? 'Aktifkan' : 'Menonaktifkan'

        Swal.fire({
            title: "Are you sure?",
            text: `${messag} Dokumen ini?`,
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
                        $.pjax.reload({container: '#registrasiGrid0', async : false})
                        calert.success(response.message)
                    } else {
                        calert.error(response.error)
                    }
                })
            }
            button.buttonFn('reset')
        });
    })
})