const baseCU = '/kategori-dokumen'
document.addEventListener("DOMContentLoaded", function(event) {
    $(document).on('click','#btn-add-docs', function(e){
        $('#btn-docs-action').attr('data-action', 'create')
        $('#modalDocsTitle').text('Tambah Data Kategori Dokumen')
        $('#modalDocs').modal('show')
    })

    $('#modalDocs').on('hidden.bs.modal', function(e){
        $('#docs-form')[0].reset()
    })

    $('#btn-docs-action').click(function(e){
        var button = $(this)
        button.buttonFn('loading')

        let state = $(this).attr('data-action')
        let form = $('#docs-form')
        let params = null

        if (state == 'update') {
            let pabID = $(this).attr('data-id')
            params = ajaxParams(pathON.createUrl(`${baseCU}/update?id=${pabID}`), 'POST', form.serialize())
        } else {
            params = ajaxParams(pathON.createUrl(`${baseCU}/create`), 'POST', form.serialize())
        }

        $.ajax(params).done(function(response) {
            if (response.status) {
                $.pjax.reload({container: '#docsGrid0', async : false})
                $('#modalDocs').modal('hide')
                calert.success(response.message)
            } else {
                calert.error(response.error)
            }
        })

        button.buttonFn('reset')
    })

    $(document).on('click', '.btn-update-docs', function(e){
        var button = $(this)
        button.buttonFn('loading')

        let pabID = $(this).attr('data-id')

        let params = ajaxParams(pathON.createUrl(`${baseCU}/get-data`), 'GET', {id: pabID})
        $.ajax(params).done(function(response) {
            if (response.status) {
                let data = response.data
                $('#btn-docs-action').attr('data-action', 'update').attr('data-id', data.iId)
                $('#modalDocsTitle').text('Update Data')
                $('#kategoridokumen-vnama').val(data.vNama)
                $('#kategoridokumen-valias').val(data.vAlias)
                $('#kategoridokumen-ilist').val(data.iList)
                $('#modalDocs').modal('show')
            } else {
                calert.error(response.error)
            }
            button.buttonFn('reset')
        })
    })

    $(document).on('click', '.btn-delete-docs', function(e){
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
                        $.pjax.reload({container: '#docsGrid0', async : false})
                        calert.success('Berhasil menghapus data')
                    } else {
                        calert.error(response.error)
                    }
                })
            }
            button.buttonFn('reset')
        });
    })
})