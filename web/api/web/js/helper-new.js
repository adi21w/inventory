(function(window) {
    "use strict"

    function Helper_Page() {
        this.initialize()
    }

    Helper_Page.prototype.initialize = function() {
        console.log('helper v1.6')

        $.fn.inputNumbering = function() {
            $(this).on('keypress', function(e){
                if ((e.which < 48) || (e.which > 57)) {
                    return false;
                }
            });
        }

        if (typeof basePATH === 'undefined') {
            console.log('basePATH is not defined. Please define basePATH before using Helper_Page.');
            this.path.base = '';
        } else {
            this.path.base = basePATH;
        }
    }

    Helper_Page.prototype.path = {
        base: null,
        trims(str, ch) {
            var start = 0, 
                end = str.length;

            while(start < end && str[start] === ch)
                ++start;

            while(end > start && str[end - 1] === ch)
                --end;

            return (start > 0 || end < str.length) ? str.substring(start, end) : str;
        },
        create(path = null, params = null) {
            if (path == null) {
                return this.base
            }
            var url = this.base + path;
            if (typeof params === 'object' && params !== null) {
                if (Object.keys(params).length > 0) {
                    url += '?'
                    $.each(params, function(key, value) {
                        url += `${key}=${encodeUrl(value)}&`;
                    });
                }
            } else if (typeof params === 'string' && params !== null) {
                url += '?'
                url += params
            }
            return this.trims(url, '&');
        }
    }

    Helper_Page.prototype.url2Obj = function() {
        let searchUrl = location.search.substring(1);
        if (searchUrl != '') {
            let url = JSON.parse('{"' + decodeURI(searchUrl).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}')
            return url;
        }

        return null
    }

    Helper_Page.prototype.createHTML = {
        buildAttr: function(attr = {}) {
            if (!attr || typeof attr !== 'object') return '';
            return Object.entries(attr)
                .map(([key, val]) => `${key}="${val}"`)
                .join(' ');
        },
        button: function(text, attr = {}) {
            if (!attr.type) attr.type = 'button';
            return `<button ${this.buildAttr(attr)}>${text}</button>`;
        },
        a: function(text, href, attr = {}) {
            if (typeof href === 'string') {
                attr.href = href;
            }
            return `<a ${this.buildAttr(attr)}>${text}</a>`;
        },
        input: function(attr = {}) {
            return `<input ${this.buildAttr(attr)}>`;
        },
        tag: function(tag, text, attr = {}) {
            return `<${tag} ${this.buildAttr(attr)}>${text}</${tag}>`;
        },
        img: function(src, attr = {}) {
            attr.src = src;
            return `<img ${this.buildAttr(attr)}>`;
        }
    }

    Helper_Page.prototype.renderView = function(data) {
        let creator = this.createHTML
        let currency = this.formatNumber
        let html = ''
        let listItem = ''
        $.each(data, function(k, v) {
            let subListItem = ''

            if (('format' in v) && (v.format == 'image')) {
                if (v.value != null) {
                    subListItem += creator.tag('div', '', {class: 'col-md-2'})
                    subListItem += creator.tag('div', creator.img(v.path+v.value, {'class': 'img-thumbnail'}), {class: 'col-md-10'})
                }
            } else {
                let checkValue = ('format' in v) && (v.format == 'currency') ? currency.angka(v.value) : v.value
    
                subListItem += creator.tag('div', creator.tag('label', v.label, {'class': 'col-form-label'}), {class: 'col-md-2'})
                subListItem += creator.tag('div', creator.input({type: 'text', value: checkValue, readonly: 'readonly', class: 'form-control'}), {class: 'col-md-10'})

            }

            listItem += creator.tag('div', subListItem, {class: 'row mb-2 align-items-center'})
                
        })

        html = listItem

        return html
    }

    Helper_Page.prototype.ajaxParams = function({url, type='GET', data={}, loader=false}) {
        const config = {
            url,
            type,
            data,
            dataType:'json',
            error(xhr, status, error){
                console.error('AJAX Error:', {
                    url,
                    status,
                    error,
                    response: xhr.responseText
                });
            }
        };

        if(loader){
            config.beforeSend = () => $('.load-box-page').show();
            config.complete   = () => $('.load-box-page').hide();
        }

        return config;
    }

    Helper_Page.prototype.ajaxCall = function({url, type, data}) {
        return $.ajax(this.ajaxParams({url: this.path.create(url), type, data}))
    }

    Helper_Page.prototype.formatNumber = {
        currency: new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }),
        number: new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0
        }),
        rupiah(value){
            return this.currency.format(value)
        },
        angka(value){
            return this.number.format(value)
        }
    }

    Helper_Page.prototype.dateFormat = {
        toDate(value) {
            return value.toLocaleString();
        },
        toID(value) {
            const tanggal = new Date(value)
            let result = tanggal.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            })

            return result
        }
    }

    Helper_Page.prototype.sweet = {
        build({text, icon, confirmButtonText = 'OK', ...rest}) {
            const config = {title: "TPS Says :", text, icon, confirmButtonText, ...rest}
            return config
        },
        success(message, button = 'OK'){
            Swal.fire(this.build({text: message, icon: 'success', confirmButtonText: button}));
        },
        error(message, button = 'Kembali'){
            Swal.fire(this.build({text: message, icon: 'error', confirmButtonText: button}));
        },
        info(message, button = 'OK'){
            Swal.fire(this.build({text: message, icon: 'info', confirmButtonText: button}));
        },
        warning(message, button = 'OK'){
            Swal.fire(this.build({text: message, icon: 'warning', confirmButtonText: button}));
        },
        toast(message, icon) {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            return Toast.fire({
                icon: icon,
                title: message
            });
        },
        loading() {
            Swal.fire({
                title: 'Menunggu...',
                text: 'Proses sedang berlangusng...',
                showConfirmButton: false, // hilangin tombol OK
                showCancelButton: false,  // hilangin tombol Cancel (default-nya emang gak ada sih)
                allowOutsideClick: false, // biar gak bisa ditutup klik luar
                allowEscapeKey: false     // biar gak bisa ditutup pakai tombol Esc
            });
        },
        close() {
            Swal.close();
        },
        confrim({text = "You won't be able to revert this!",  confirmButtonText = "Yes, delete it!"}) {
            return {
                title: "Are you sure?",
                text,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#59adff",
                cancelButtonColor: "#ff4557",
                confirmButtonText
            }
        }
    }

    Helper_Page.prototype.toastify = function({text, duration = 3000, close = true, gravity = "top", position = "center"}) {
        return Toastify({text, duration, close, gravity, position}).showToast();
    }

    Helper_Page.prototype.createSlug = function(text, deli="-") {
        // Buang semua karakter selain huruf, angka, spasi, dan tanda hubung
        let clean = text.replace(/[^\w\s-]/g, '');
        // Ganti spasi dan underscore jadi delimiter
        clean = clean.replace(/[\s_]+/g, deli);
        // Ganti multiple delimiter jadi satu
        clean = clean.replace(new RegExp(`${deli}+`, 'g'), deli);
        // Trim delimiter di awal/akhir
        clean = clean.replace(new RegExp(`^${deli}|${deli}$`, 'g'), '');
        return clean;
    }

    Helper_Page.prototype.reloadGrid = function(grid = "#tableGrid0") {
        return new Promise((resolve) => {
            $(document).one('pjax:end', function () {
                resolve();
            });
            $.pjax.reload({ container: grid, push: false })
        })
    }

    Helper_Page.prototype.loadForm = function(model, data) {
        $.each(data, function(k,v){
            let lowstr = k.toLowerCase()
            let inputs = $(`#${model}-${lowstr}`)

            if (inputs.length > 0) {
                if (inputs.is('select')) {
                    if (inputs.hasClass('select2-hidden-accessible')){
                        if (inputs.prop('multiple')) {
                            let split = v == null ? [] : v.split(',')
                            v = split
                        } else if (inputs.is('[data-search]')) {
                            let relation = inputs.attr(`data-search`)
                            let htopt = createHTML.tag('option', data[relation], {value: v})
                            inputs.html(htopt)
                        }
    
                        inputs.val(v).change()
                    } else {
                        inputs.val(v)
                    }
                } else {
                    inputs.val(v)
                }
            }
        })
    }

    Helper_Page.prototype.parsingURL = function(){
        const fullPath = window.location.pathname; // misalnya "/reg_akl/frontend/web/produk/index"
        const basePath = pathON.base;
        const relativePath = fullPath.replace(basePath, "");

        return relativePath;
    }

    const helper = new Helper_Page();

    Object.defineProperty(window, '$helper', {
        value: helper,
        writable: false
    });
})(window)