const baseCU = '/produk-web'
const loadAllButton = function() {
    $('.select-picture-produk').removeClass('btn-light disabled').addClass('btn-primary')
    return
}
document.addEventListener("DOMContentLoaded", function(event) {
    $(document).on('click', '.btn-aktif-produk', function(e){
        var button = $(this)
        button.buttonFn('loading')

        let pabID = $(this).attr('data-id')
        let pabSts = $(this).attr('data-active')
        let messag = pabSts == 'Ya' ? 'Publish' : 'Sembunyikan'

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
                let params = ajaxParams(pathON.createUrl(`${baseCU}/publish`), 'GET', {id: pabID, status: pabSts})
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

    $('.select-picture-produk').click(function(e){
        let baseTag = $(this)
        let urlobj = url2Obj()
        let image = $(this).attr('data-id')
        let produk = urlobj.id

        Swal.fire({
            title: "Are you sure?",
            text: `Gunakan Foto Produk ini?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#59adff",
            cancelButtonColor: "#ff4557",
            confirmButtonText: "Yes, run it!"
        }).then((result) => {
            if (result.isConfirmed) {
                loadAllButton()
                let params = ajaxParams(pathON.createUrl(`${baseCU}/select-image`), 'GET', {id: image, produk})
                $.ajax(params).done(function(response) {
                    if (response.status) {
                        baseTag.removeClass('btn-primary').addClass('btn-light disabled')
                        calert.success(response.message)
                    } else {
                        calert.error(response.error)
                    }
                })
            }
        });
    })
})