const baseCU = '/dokumen'
document.addEventListener("DOMContentLoaded", function(event) {
    $(document).on('click','#btn-add-dokumen', function(e){
        $('#btn-dokumen-action').attr('data-action', 'create')
        $('#modalDokumenTitle').text('Tambah Dokumen')
        $('#modalDokumen').modal('show')
    })

    $('#modalDokumen').on('hidden.bs.modal', function(e){
        $('#name-for-file').text('')
        $('#name-for-file').attr('title', '')
        $('#name-for-file').attr('data-docs', '')
        $('#dokumen-form')[0].reset()
    })

    $('#btn-dokumen-action').click(function(e){
        var button = $(this)
        button.buttonFn('loading')

        let state = $(this).attr('data-action')
        let form = $('#dokumen-form')
        let params = null

        if (state == 'update') {
            let pabID = $(this).attr('data-id')
            params = ajaxParams(pathON.createUrl(`${baseCU}/update?id=${pabID}`), 'POST', form.serialize())
        } else if (state == 'extend') {
            let pabID = $(this).attr('data-id')
            params = ajaxParams(pathON.createUrl(`${baseCU}/extend`, {id: pabID}), 'POST', form.serialize())
        } else {
            params = ajaxParams(pathON.createUrl(`${baseCU}/create`), 'POST', form.serialize())
        }

        $.ajax(params).done(function(response) {
            if (response.status) {
                $.pjax.reload({container: '#dokumenGrid0', async : false})
                $('#modalDokumen').modal('hide')
                calert.success(response.message)
            } else {
                calert.error(response.error)
            }
        })

        button.buttonFn('reset')
    })

    $(document).on('click', '.btn-update-dokumen', function(e){
        var button = $(this)
        button.buttonFn('loading')

        let pabID = $(this).attr('data-id')

        let params = ajaxParams(pathON.createUrl(`${baseCU}/get-data`), 'GET', {id: pabID})
        $.ajax(params).done(function(response) {
            if (response.status) {
                let data = response.data
                $('#btn-dokumen-action').attr('data-action', 'update').attr('data-id', pabID)
                $('#modalDokumenTitle').text('Update Data')
                $('#dokumen-dstart').val(data.start)
                $('#dokumen-dexpired').val(data.expired)
                $('#dokumen-ecompany').val(data.company)
                $('#dokumen-ejenis').val(data.jenis)
                $('#dokumen-tketerangan').val(data.keterangan)
                $('#dokumen-vlink').val(data.link)
                $('#dokumen-vfilename').val(data.file)
                $('#name-for-file').text(data.file).attr('title', data.file)

                let check = $('#dokumen-ivendorid').find(`option[value="${data.vid}"]`).length
                if (check == 0) {
                    let htopt = createHTML.tag('option', data.vendor, {value: data.vid})
                    $('#dokumen-ivendorid').append(htopt)
                }
                $('#dokumen-ivendorid').val(data.vid).change()
                $('#modalDokumen').modal('show')
            } else {
                calert.error(response.error)
            }
            button.buttonFn('reset')
        })
    })

    $(document).on('click', '.btn-extend-dokumen', function(e){
        var button = $(this)
        button.buttonFn('loading')

        let pabID = $(this).attr('data-id')

        let params = ajaxParams(pathON.createUrl(`${baseCU}/get-data`), 'GET', {id: pabID})
        $.ajax(params).done(function(response) {
            if (response.status) {
                let data = response.data
                $('#btn-dokumen-action').attr('data-action', 'extend').attr('data-id', pabID)
                $('#modalDokumenTitle').text('Tambah Data (Extend)')
                $('#dokumen-ecompany').val(data.company)
                $('#dokumen-ejenis').val(data.jenis)

                let check = $('#dokumen-ivendorid').find(`option[value="${data.vid}"]`).length
                if (check == 0) {
                    let htopt = createHTML.tag('option', data.vendor, {value: data.vid})
                    $('#dokumen-ivendorid').append(htopt)
                }
                $('#dokumen-ivendorid').val(data.vid).change()
                $('#modalDokumen').modal('show')
            } else {
                calert.error(response.error)
            }
            button.buttonFn('reset')
        })
    })

    $(document).on('click', '.btn-delete-dokumen', function(e){
        var button = $(this)
        button.buttonFn('loading')

        let pabID = $(this).attr('data-id')

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#59adff",
            cancelButtonColor: "#ff4557",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                let params = ajaxParams(pathON.createUrl(`${baseCU}/delete`), 'GET', {id: pabID})
                $.ajax(params).done(function(response) {
                    if (response.status) {
                        $.pjax.reload({container: '#dokumenGrid0', async : false})
                        calert.success('Berhasil menghapus data')
                    } else {
                        calert.error(response.error)
                    }
                })
            }
            button.buttonFn('reset')
        });
    })

    $(document).on('click', '.btn-aktif-dokumen', function(e){
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
                        $.pjax.reload({container: '#dokumenGrid0', async : false})
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