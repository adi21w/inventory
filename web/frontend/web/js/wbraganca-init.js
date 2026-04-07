document.addEventListener("DOMContentLoaded", function(event) {
    $(".dynamicform_wrapper").on("afterInsert", async function(e, item) {
        // await sleep(1000)
        await $('[data-format="number"]').inputNumbering()
        let globalVar = $('.dynamicform_wrapper').eq(0).attr('data-dynamicform');
        let getVar = window[globalVar].fields
        let panelLength = $('.item.panel').length
    
        if (panelLength > 0) {
            panelLength = panelLength - 1
        } else {
            return
        }
    
        for (var i = 0; i < getVar.length; i++){
            var getTemp = getVar[i].id
            var idTemp = getTemp.split('{}')
            var newTemp = idTemp[0]+panelLength+idTemp[1]

            if ($('#'+newTemp).is('select')) {
                if ($('#'+newTemp).hasClass('select2-hidden-accessible')){
                    let placeholder = $('#'+newTemp).find('option').eq(0).text()
                    await $('#'+newTemp).val('').change()
                    await $('#select2-'+newTemp+'-container').attr('title', '').html('<span class="select2-selection__placeholder">'+placeholder+'</span>')
                } else {
                    let checkSave = await $('#'+newTemp).attr('data-save-value')
                    if ((checkSave == undefined)) {
                        await $('#'+newTemp).val('')
                    }
                }
            } else if ($('#'+newTemp).is('input')) {
                if ($('#'+newTemp).attr('type') == 'checkbox') {
                    await $('#'+newTemp).prop('checked', false)
                } else if ($('#'+newTemp).attr('type') == 'radio') {
                    await $('#'+newTemp).prop('checked', false)
                } else if (($('#'+newTemp).attr('type') == 'text')) {
                    let checkSave = await $('#'+newTemp).attr('data-save-value')
                    let attr = $('#'+newTemp).attr('data-default')
                    let attrMask = $('#'+newTemp).attr('data-mask')
                    let attrMask2 = $('#'+newTemp).attr('data-plugin-inputmask')
                    let attrDatePickerKv = $('#'+newTemp).attr('data-krajee-kvdatepicker')
                    // console.log(attr, attrMask, attrMask2)
                    if (checkSave !== 'undefined') {
                        let defVal = $('#'+newTemp).attr('data-default-value')
                        await $('#'+newTemp).val(defVal)
                    } 

                    if (typeof attr !== 'undefined' && attr !== false) {
                        await $('#'+newTemp).val(attr)
                    }

                    if (typeof attrMask !== 'undefined' && attrMask !== false) {
                        await $('#'+newTemp).inputmask({
                            alias : 'decimal',
                            digits : 2,
                            "text-Align" : 'left',
                            digitsOptional : true,
                            groupSeparator : '.',
                            autoGroup : true,
                            removeMaskOnSubmit : true,
                        })
                    }
    
                    if (typeof attrMask2 !== 'undefined' && attrMask2 !== false) {
                        await $('#'+newTemp).inputmask(window[attrMask2])
                    }

                    if (typeof attrDatePickerKv !== 'undefined' && attrDatePickerKv !== false) {
                        if ($('#'+newTemp).data('kvDatepicker')) {
                            await jQuery('#'+newTemp).kvDatepicker('destroy')
                        }
                        await $('#'+newTemp).kvDatepicker(window[attrDatePickerKv])
                    }
                } else if (($('#'+newTemp).attr('type') == 'hidden')) {
                    let checkSave = await $('#'+newTemp).attr('data-save-value')
                    if (checkSave !== 'undefined') {
                        let defVal = $('#'+newTemp).attr('data-default-value')
                        await $('#'+newTemp).val(defVal)
                    } 
                }
            }
        }
    
        let serialNumber = $('.place-number').length
        if (serialNumber > 0) {
            $('.item.panel').each(function(k) {
                $('.place-number').eq(k).text(k+1)
            })
        }
    });

    $(".dynamicform_wrapper").on("afterDelete", async function(e, item) {
        let serialNumber = $('.place-number').length
        if (serialNumber > 0) {
            $('.item.panel').each(function(k) {
                $('.place-number').eq(k).text(k+1)
            })
        }
    })
})