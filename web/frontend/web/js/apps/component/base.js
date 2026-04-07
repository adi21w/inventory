const configPage = {
    url(){
        return $('#tableGrid0').attr('data-home')
    },
    title() {
        return $('#main-content h3').eq(0).text()
    }
}

let loadingVendor = false;
document.addEventListener("DOMContentLoaded", function(event) {
    $(document).on('pjax:start', '#tableGrid0', () => loadingVendor = true);
    $(document).on('pjax:end',   '#tableGrid0', () => loadingVendor = false);

    $(document).on('click','#btn-add-grid', function(e){
        $('#btn-grid-action').attr('data-action', 'create')
        $('#modalGridTitle').text(`Add ${configPage.title()}`)
        $('#modalGrid').modal('show')
    })

    $('#modalGrid').on('hidden.bs.modal', function(e){
        $('#grid-form')[0].reset()
    })

    $('#btn-grid-action').click(function(e){
        var button = $(this)
        button.buttonFn('loading')
        let form = $('#grid-form')
        let state = $(this).attr('data-action')
        let pabID = $(this).attr('data-id')

        const url = state === 'update' ? `${configPage.url()}/update?id=${pabID}` : `${configPage.url()}/create`

        $helper.ajaxCall({url, type: 'POST', data: form.serialize()})
        .done(async (res) => {
            if (res.status) {
                $('#modalGrid').modal('hide')
                $helper.sweet.success(res.message)
                await $helper.reloadGrid()
            } else {
                $helper.sweet.error(res.error)
            }
        })
        .always(() => button.buttonFn('reset'))
    })

    $(document).on('click', '.btn-update-grid', function(e){
        var button = $(this)
        let pabID = $(this).attr('data-id')

        button.buttonFn('loading')

        $helper.ajaxCall({url: `${configPage.url()}/get-data`, data:{id:pabID}})
        .done((res) => {
            if (!res.status) {
                $helper.sweet.error(res.error)
                return
            }

            let data = res.data
            $('#btn-grid-action').attr('data-action', 'update').attr('data-id', pabID)
            $('#modalGridTitle').text(`Update  ${configPage.title()}`)
            $helper.loadForm($('#tableGrid0').attr('data-model'), data)
            $('#modalGrid').modal('show')
        })
        .always(() => button.buttonFn('reset'))
    })

    $(document).on('click', '.btn-delete-grid', function(e){
        var button = $(this)
        let pabID = $(this).attr('data-id')
        
        button.buttonFn('loading')

        Swal.fire($helper.sweet.confrim({})).then((result) => {
            if (!result.isConfirmed) {
                button.buttonFn('reset')
                return
            }

            $helper.ajaxCall({url: `${configPage.url()}/delete`, data: {id:pabID}})
            .done(async (res) => {
                if (res.status) {
                    $helper.sweet.success('Berhasil menghapus data')
                    await $helper.reloadGrid()
                } else {
                    $helper.sweet.error(res.error)
                }
            })
            .always(() => button.buttonFn('reset'))
        });
    })

    $(document).on('click', '.btn-status-grid', function(e){
        var button = $(this)
        let pabID = $(this).attr('data-id')
        let pabSts = $(this).attr('data-active')
        let pabMsg = $(this).attr('data-message')

        button.buttonFn('loading')

        Swal.fire($helper.sweet.confrim({text: pabMsg, confirmButtonText: "Yes, run it!"})).then((result) => {
            if (!result.isConfirmed) {
                button.buttonFn('reset')
                return
            }

            $helper.ajaxCall({url: `${configPage.url()}/status`, data: {id:pabID, status:pabSts}})
            .done(async (res) => {
                if (res.status) {
                    $helper.sweet.success('Berhasil merubah status data')
                    await $helper.reloadGrid()
                } else {
                    $helper.sweet.error(res.error)
                }
            })
            .always(() => button.buttonFn('reset'))
        });
    })

    $(document).on('click', '.btn-view-grid', function(e){
        var button = $(this)
        let pabID = $(this).attr('data-id')

        button.buttonFn('loading')

        $helper.ajaxCall({url: `${configPage.url()}/get-view`, data:{id:pabID}})
        .done((res) => {
            if (!res.status) {
                $helper.sweet.error(res.error)
                return
            }

            let render = $helper.renderView(res.data)
            $('#view-title-grid small').text(`${configPage.title()} Detail`)
            $('#view-content-grid').html(render)
            $('#view-page-grid').removeClass('d-none')
            setTimeout(() => {
                $('#view-page-grid').removeClass('opacity-0');
            }, 10);

            $('html, body').animate({
                scrollTop: $('#view-page-grid').offset().top
            }, 600); // 600ms biar smooth
        })
        .always(() => button.buttonFn('reset'))
    })
})