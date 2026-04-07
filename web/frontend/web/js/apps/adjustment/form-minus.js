const baseCU = '/adjustment-minus'
let enabledWare
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

const tempBatch = {
    list: {},
    add(key, data) {
        if (!this.list.hasOwnProperty(key)) this.list[key] = data
    },
    get(key) {
        return this.list[key]
    },
    async init() {
        $helper.sweet.loading()
        this.list = {}

        let ids = tempProduk.list
        let gudang = $('#tadjustmenthd-igudangid').val()

        await $helper.ajaxCall({url: `/stock/get-view`, data:{id: ids, gudang, template: 1}})
        .then((res) => {
            if (!res.status) {
                $helper.sweet.error(res.error)
                return
            }

            $.each(res.data, function(k, v) {
                console.log(v)
                let batch = $('[data-input="batch"]').eq(k).val()
                let options = ''
                $.each(v, function(x,y) {
                    // console.log(y)
                    let databatch = {batch: y.vBatch, expired: y.dExpired, qty: y.iQtysum}
                    tempBatch.add(databatch.batch, databatch)
                    options += $helper.createHTML.tag('option', databatch.batch, {value: databatch.batch, selected: batch == databatch.batch ? true : false})
                })

                $('[data-input="batch"]').eq(k).html(options)
            })

            return $helper.sweet.close()
        })
        .catch((err) => {
            $helper.sweet.error(err.message || 'Terjadi kesalahan')
        })
    }
}

document.addEventListener("DOMContentLoaded", function(event) {
    $('#tadjustmenthd-igudangid').change(function(e){
        let gudang = $(this).val()
        if (!gudang) {
            $('#block-layer').show()
            return
        }

        $('#block-layer').hide()
        let selectedOption = $(this).find('option:selected').text()

        $(this).off('change')
        $(this).html($helper.createHTML.tag('option', selectedOption, {value: gudang}))
        $(this).on('change', arguments.callee)
        $(this).trigger('change.internal');
    })

    $(document).on('change', '[data-input="produk"]', async function(e){
        let dynamicID = $helper.dynamicGetID($(this))
        let produk = $(this).val()
        let gudang = $('#tadjustmenthd-igudangid').val()
        if (!produk) {
            return
        }
        
        $helper.sweet.loading()

        if (tempProduk.list.includes(produk)) {
            $helper.sweet.warning('Produk sudah terdaftar')
            return $(this).val('').change()
        }

        await $helper.ajaxCall({url: `/products/get-view`, data:{id:produk, template: 1}})
        .then((res) => {
            if (!res.status) {
                $helper.sweet.error(res.error)
                return
            }

            let data = res.data
            let html = $helper.createHTML.tag('option', data.small.label, {value: data.small.value})
            $('[data-input="kemasan"]').eq(dynamicID).html(html)

            return $helper.ajaxCall({
                url: `/stock/get-view`, 
                data: { id: produk, gudang, template: 0 }
            })
        })
        .then((response) => {
            let options = ''
            $.each(response.data, function(k,v){
                options += $helper.createHTML.tag('option', v.batch, {value: v.batch})
                tempBatch.add(v.batch, v)
            })

            $('[data-input="batch"]').eq(dynamicID).html(options).change()

            $helper.sweet.close()
        })
        .catch((err) => {
            $helper.sweet.error(err.message || 'Terjadi kesalahan')
        })

        tempProduk.add(produk)
    })

    $(document).on('change', '[data-input="batch"]', function(e){
        let dynamicID = $helper.dynamicGetID($(this))
        let batch = $(this).val()

        if (!batch) {
            return
        }
        let data = tempBatch.get(batch)

        $('[data-input="expired"]').eq(dynamicID).val(data.expired)
        $('[data-input="qty"]').eq(dynamicID).val(data.qty).attr('data-max', data.qty)
    })

    $(document).on('change', '[data-input="qty"]', function(e){
        let dynamicID = $helper.dynamicGetID($(this))
        let qty = $(this).val()

        if (!qty) {
            return
        }

        let maxQty = $(this).attr('data-max')
        if (parseInt(qty) > parseInt(maxQty)) {
            $helper.sweet.warning('Qty tidak boleh melebihi dari '+maxQty)
            $(this).val(maxQty)
            return 
        }
    })

    $(".dynamicform_wrapper").on("afterDelete", function(e, item) {
        tempProduk.init()
    })

    if (typeof onUpdate != 'undefined') {
        tempProduk.init()
        tempBatch.init()
        $('#block-layer').hide()
    }

    $('[data-input="qty"]').inputNumbering()
})