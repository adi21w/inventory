const baseCU = '/adjustment-minus'

const tempProduk = {
    list: [],
    add(data) {
        this.list.push(data)
    },
    build() {
        $('[data-input="produk"]').each(function(index){
            let val = $(this).val()

            if (val) {
                tempProduk.add(val)
            }
        })
    },
    init() {
        this.list = []
        this.build()
    }
}

document.addEventListener("DOMContentLoaded", function(event) {
    $(document).on('change', '[data-input="produk"]', function(e){
        let dynamicID = $helper.dynamicGetID($(this))
        let produk = $(this).val()
        if (!produk) {
            return
        }

        if (tempProduk.list.includes(produk)) {
            $helper.sweet.warning('Produk sudah terdaftar')
            return $(this).val('').change()
        }

        $helper.ajaxCall({url: `/products/get-view`, data:{id:produk, template: 1}})
        .done((res) => {
            if (!res.status) {
                $helper.sweet.error(res.error)
                return
            }

            let data = res.data
            let html = $helper.createHTML.tag('option', data.big.label, {value: data.big.value})
                html += $helper.createHTML.tag('option', data.small.label, {value: data.small.value})

            $('[data-input="kemasan"]').eq(dynamicID).html(html)
        })

        tempProduk.add(produk)
    })

    $(document).on('change', '[data-input="batch"]', function(e){
        let batch = $(this).val()
        if (!batch) {
            return
        }

        let upper = batch.toUpperCase()
        $(this).val(upper)
    })

    $(".dynamicform_wrapper").on("afterDelete", function(e, item) {
        tempProduk.init()
    })

    if (typeof onUpdate != 'undefined') {
        tempProduk.init()
    }

    $('[data-input="qty"]').inputNumbering()
})