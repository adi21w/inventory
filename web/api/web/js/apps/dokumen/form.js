function createNameFile(name = null, state = false) {
    let oldName = $('#name-for-file').attr('data-docs')
    let partslug = name == null ? oldName : name
    let slug = createSLUGX(partslug)

    $('[name="Dokumen[vFileName]"]').eq(0).val(slug)
    $('#name-for-file').text(slug)
    $('#name-for-file').attr('title', slug)
    
    if (state) {
        $('#name-for-file').attr('data-docs', slug)
    }
}

document.addEventListener("DOMContentLoaded", function(event) {
    $('#generate-name-file').click(function(e){
        var button = $(this)
        button.buttonFn('loading')
        let flagType = $(this).attr('data-flag')

        let vendor = $('[name="Dokumen[iVendorId]"]').eq(0).val()
        let nie = $('[name="Dokumen[vNIE]"]').eq(0).val()
        let kategori = $('[name="Dokumen[iKategoriId]"]').eq(0).val()
        let company = $('[name="Dokumen[eCompany]"]').eq(0).val()
        let jenis = $('[name="Dokumen[eJenis]"]').eq(0).val()
        let expired = $('[name="Dokumen[dExpired]"]').eq(0).val()
        let extend = $('#dokumen-eextend').prop('checked')

        if (!extend) {
            if (expired == ''){
                button.buttonFn('reset')
                return calert.info('Expired tidak boleh kosong!')
            }
        }

        if (flagType == 1) {
            if (nie == ''){
                button.buttonFn('reset')
                return calert.info('Nomor tidak boleh kosong!')
            }
            if (kategori < 1){
                button.buttonFn('reset')
                return calert.info('Kategori tidak boleh kosong!')
            }
        } else {
            if (vendor == ''){
                button.buttonFn('reset')
                return calert.info('Vendor tidak boleh kosong!')
            }
        }

        let fullname = ''
        let akhir = expired.split('-').reverse().join('-');
        if (flagType == 1) {
            let nkategori = $('[name="Dokumen[iKategoriId]"]').eq(0).find('option:selected').text()
            if (jenis == 'AKL' || jenis == 'AKD') {
                fullname = `${nie} ${nkategori} ${company} ${akhir}`
            } else {
                fullname = `${jenis} ${nie} ${nkategori} ${company} ${akhir}`
            }
        } else {
            let nvendor = $('[name="Dokumen[iVendorId]"]').eq(0).find('option:selected').text()
            if (extend) {
                fullname = `${jenis} ${nvendor} ${company}`
            } else {
                fullname = `${jenis} ${nvendor} ${company} ${akhir}`
            }
        }
        createNameFile(fullname)
        button.buttonFn('reset')

    })

    if ((typeof newRecord !== 'undefined') && (newRecord == 0)) {
        if ($('[name="Dokumen[vFileName]"]').eq(0).val() == '') {
            createNameFile(null, true)
        }
    }

    $('#dokumen-ivendorid').change(function(){
        let pabID = $(this).val()

        let params = ajaxParams(pathON.createUrl(`/api/brand-kategori`), 'GET', {id: pabID})
        $.ajax(params).done(function(response) {
            if (response.status) {
                let data = response.data
                let html = ''
                $.each(data, function(k,v){
                    html += createHTML.tag('option', v.brand, {value: v.id})
                })

                $('#dokumen-ikategoriid').html(html)
            } else {
                calert.error(response.error)
            }
            button.buttonFn('reset')
        })
    })
})