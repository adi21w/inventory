const autoCategory = function() {
    let valBrand = $('#kategoriproduk-ibrandid').val()
    let brand = $('#kategoriproduk-ibrandid').find('option:checked').text()
    if (!valBrand) {
        return
    }

    let kategori = $('#kategoriproduk-vkategori').val()
    if (!kategori) {
        return
    }
    
    let newCat = `${brand} ${kategori}`

    $('#kategoriproduk-valias').val(newCat)
}

document.addEventListener("DOMContentLoaded", function(event) {
    $('#kategoriproduk-ibrandid, #kategoriproduk-vkategori').change(function(e){
        autoCategory()
    })
})