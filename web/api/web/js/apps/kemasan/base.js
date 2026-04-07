const baseCU = '/kemasan'
document.addEventListener("DOMContentLoaded", function(event) {
    $(document).on('click','#btn-add-kemasan', function(e){
        $('#btn-kemasan-action').attr('data-action', 'create')
        $('#modalKemasanTitle').text('Tambah Data Kemasan')
        $('#modalKemasan').modal('show')
    })

    $('#modalKemasan').on('hidden.bs.modal', function(e){
        $('#docs-form')[0].reset()
    })

    $('#btn-kemasan-action').click(function(e){
        var button = $(this)
        button.buttonFn('loading')

        let state = $(this).attr('data-action')
        let form = $('#kemasan-form')
        let params = null

        if (state == 'update') {
            let pabID = $(this).attr('data-id')
            params = ajaxParams(pathON.createUrl(`${baseCU}/update?id=${pabID}`), 'POST', form.serialize())
        } else {
            params = ajaxParams(pathON.createUrl(`${baseCU}/create`), 'POST', form.serialize())
        }

        $.ajax(params).done(function(response) {
            if (response.status) {
                $.pjax.reload({container: '#kemasanGrid0', async : false})
                $('#modalKemasan').modal('hide')
                calert.success(response.message)
            } else {
                calert.error(response.error)
            }
        })

        button.buttonFn('reset')
    })

    $(document).on('click', '.btn-update-kemasan', function(e){
        var button = $(this)
        button.buttonFn('loading')

        let pabID = $(this).attr('data-id')

        let params = ajaxParams(pathON.createUrl(`${baseCU}/get-data`), 'GET', {id: pabID})
        $.ajax(params).done(function(response) {
            if (response.status) {
                let data = response.data
                $('#btn-kemasan-action').attr('data-action', 'update').attr('data-id', data.iId)
                $('#modalKemasanTitle').text('Update Data')
                $('#kemasan-vnama').val(data.vNama)
                $('#kemasan-tketerangan').val(data.tKeterangan)
                $('#modalKemasan').modal('show')
            } else {
                calert.error(response.error)
            }
            button.buttonFn('reset')
        })
    })

    $(document).on('click', '.btn-delete-kemasan', function(e){
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
                        $.pjax.reload({container: '#kemasanGrid0', async : false})
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