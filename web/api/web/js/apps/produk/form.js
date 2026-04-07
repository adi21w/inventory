document.addEventListener("DOMContentLoaded", function(event) {
    $('#products-vnama').change(function(e){
        let nama = $(this).val()
        if (nama == '') {
            $('#products-vslug').val('')
            return $helper.sweet.error('Nama produk tidak boleh kosong!')
        }

        let slug = $helper.createSlug(nama, '-')
        $('#products-vslug').val(slug.toLowerCase())
        return
    })
})