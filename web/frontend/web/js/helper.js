// Create By Adi Widarjo
var createHTML = {
    button : function(text, attr = null){
        let attribute = '';
        let html;
        if (typeof attr === 'object' && attr !== null) {
            if (Object.keys(attr).length > 0) {
                if (!attr.hasOwnProperty('type')) {
                    attr.type = 'button';
                }
        
                $.each(attr, function(key, value) {
                    attribute += `${key}="${value}"`;    
                });

                html = `<button ${attribute}>${text}</button>`;
            } else {
                html = `<button>${text}</button>`;
            }
        } else {
            html = `<button>${text}</button>`;
        }
        
        return html;
    },
    a : function(text, link, attr = null){
        let attribute = '';
        let html;

        if (typeof link === 'object' && link !== null) {
            if (Object.keys(link).length > 0) {
                $.each(link, function(key, value) {
                    if (key == "href") {
                        attribute += `${key}="${pathON.createUrl(value)}?`;
                    } else {
                        attribute += `${key}=${value}&`;
                    }
                });
                attribute = attribute.replace(/^\?+|\?+$/g, '');
                attribute = attribute.replace(/^\&+|\&+$/g, '');
                attribute += '"';
            }
        }

        if (typeof attr === 'object' && attr !== null) {
            if (Object.keys(attr).length > 0) {
                $.each(attr, function(key, value) {
                    if (key != 'href') {
                        attribute += `${key}="${value}"`;
                    }    
                });
            }
        }

        html = `<a ${attribute}>${text}</a>`;
        
        return html;
    },
    input : function(data) {
        let attribute = '';

        if (typeof data === 'object' && data !== null) {
            if (Object.keys(data).length > 0) {
                $.each(data, function(key, value) {
                    attribute += `${key}="${value}"`;
                });
            }
        }

        html = `<input ${attribute}>`;
        return html;
    },
    tag : function(tag, text, attr = null) {
        let attribute = '';
        let html;
        if (typeof attr === 'object' && attr !== null) {
            if (Object.keys(attr).length > 0) {
                $.each(attr, function(key, value) {
                    attribute += `${key}="${value}" `;    
                });

                html = `<${tag} ${attribute}>${text}</${tag}>`;
            } else {
                html = `<${tag}>${text}</${tag}>`;
            }
        } else {
            html = `<${tag}>${text}</${tag}>`;
        }
        
        return html;
    },
    img : function(link, attr = null) {
        let attribute = '';
        let html;

        if (typeof attr === 'object' && attr !== null) {
            if (Object.keys(attr).length > 0) {
                $.each(attr, function(key, value) {
                    attribute += `${key}="${value}" `;
                });
            }
        }

        attribute += `src="${link}"`

        html = `<img ${attribute}>`;
        
        return html;
    }
}

function ajaxParams(url, tipe, params, loader = true) {
    if (loader) {
        return {
            url : url,
            type : tipe,
            data: params,
            dataType : 'JSON',
            beforeSend: function() { $('.load-box-page').show() },
            error : function(request,error)
            {
                $('.load-box-page').hide()
                console.log(request)
                console.log(error)
            },
            complete: function() { $('.load-box-page').hide() }
        }
    } else {
        return {
            url : url,
            type : tipe,
            data: params,
            dataType : 'JSON',
            error : function(request,error)
            {
                console.log(request)
                console.log(error)
            }
        }
    }
}

var createForm = {
    form : function(action, method) {
        let theForm = document.createElement('form')
        theForm.action = action
        theForm.method = method

        return theForm
    }, 
    input: function(data) {
        let newInput1 = document.createElement('input');
        newInput1.type = data.type;
        newInput1.name = data.name;
        newInput1.value = data.value;
    }
}

const formatter = new Intl.NumberFormat('id', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
})

const formatter2 = new Intl.NumberFormat();

const formatLocal = function(value) {
    return value.toLocaleString();
}

const encodeHtml = function(text) {
    if (typeof text === 'string') {
        var map = {
            '&': 'amp;',
            '<': 'lt;',
            '>': 'gt;',
            '"': 'quot;',
            "'": '#039;'
        }
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
    return text
}

const decodeHtml = function(text) {
    if (typeof text === 'string') {
        return text
            .replace(/amp;/g, "&")
            .replace(/lt;/g, "<")
            .replace(/gt;/g, ">")
            .replace(/quot;/g, '"')
            .replace(/#039;/g, "'");
    }
    return text
}

const encodeUrl = function(text) {
    if (typeof text === 'string') {
        return text
            .replace(/'/g, "%27")
            .replace(/&/g, "%26")
            .replace(/=/g, "%3D")
            .replace(/ /g, "%20")
            .replace(/!/g, "%21")
            .replace(/"/g, "%22")
            .replace(/`/g, "%60")
            .replace(/\(/g, "%28")
            .replace(/\)/g, "%29")
            .replace(/,/g, "%2C")
    }
    return text
}

const decodeUrl = function(text) {
    if (typeof text === 'string') {
        return text
            .replace(/%27/g, "'")
            .replace(/%26/g, "&")
            .replace(/%3D/g, "=")
            .replace(/\+/g, " ")
            .replace(/%20/g, " ")
    }
    return text
}

const clearConsole = function() { 
    if(window.console || window.console.firebug) {
       console.clear();
    }
}

const createSlug = function(words) {
    let a = words.toLowerCase()
    let b = a.replaceAll(' ', '-')
    return b
}

const cekFloat = function(teks, show = false) {
    if (isNaN(parseFloat(teks))) {
        return false
    }
    
    teks = teks.toString()
    if (teks.includes('.')) {
        let flo = parseFloat(teks)
        if (show) {
            return flo
        } else {
            return true
        }
    }

    return false
}

const getToken = function (){
    for (let i = 0; i < 999; i++) {
        let token = Math.floor(Math.random()*100000+1)
        let checking = token.toString().length
        if (checking == 5) {
            return token
        }
    }
}

$.fn.inputNumbering = function() {
    $(this).on('keypress', function(e){
        if ((e.which < 48) || (e.which > 57)) {
            return false;
        }
    });
}

const isIntNumber = function (n){
    return Number(n) === n && n % 1 === 0;
}

const isFloatNumber = function (n){
    return Number(n) === n && n % 1 !== 0;
}

const url2Obj = function() {
    let searchUrl = location.search.substring(1);
    if (searchUrl != '') {
        let url = JSON.parse('{"' + decodeURI(searchUrl).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}')
        return url;
    }

    return null

}

const parsingURL = function(){
    const fullPath = window.location.pathname; // misalnya "/reg_akl/frontend/web/produk/index"
    const basePath = pathON.base;
    const relativePath = fullPath.replace(basePath, "");

    return relativePath;
}

const trims = function (str, ch) {
    var start = 0, 
        end = str.length;

    while(start < end && str[start] === ch)
        ++start;

    while(end > start && str[end - 1] === ch)
        --end;

    return (start > 0 || end < str.length) ? str.substring(start, end) : str;
}

function capitalize(s)
{
    return s && s[0].toUpperCase() + s.slice(1);
}

function capitalizeFLetter(str) {
    return str[0].toUpperCase() + str.slice(1)
}

var alertB = {
    ready: null,
    fire: function(data){
        var temp = this.create(data)
        if (data.position === 1) {
            $(data.target).prepend(temp)
        } else {
            $(data.target).append(temp)
        }

        return this.trash()
    },
    create: function(data) {
        var count = $('.alert').length
        this.ready = 'alertB-'+count

        var strong = createHTML.tag('strong', capitalizeFLetter(data.type) + '!', {})
        if (data.type == 'error') {
            data.type = 'danger'
        }

        var div = createHTML.tag('div', `${strong} ${data.message}`, {id: 'alertB-'+count, class : 'alert alert-'+data.type+' alert-dismissible'})
        return div
    },
    trash: function() {
        var trashed = this.ready
        setTimeout(function(){
            $('#'+trashed).remove()
        }, 4000)
    }
}

const dynamicGetID = function(data) {
    let ids = data.attr('id')
    let expl = ids.split('-')

    return expl[1]
}

const validateNumberMe = function(number, flag = 'i') {
    let result = 0;
    if (flag == 'i') {
        result = parseInt(number)
    } else {
        result = parseFloat(number)
    }

    if (isNaN(result)) {
        return 0
    }

    return result
}

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode != 46 && charCode > 31 &&
        (charCode < 48 || charCode > 57))
        return false;

    return true;
}

function isNull($val, $numb = false) {
    if ($val == null) {
        if ($numb) {
            return 0
        } else {
            return ''
        }
    }

    return $val
}

function valueValid(val, number = true) {
    if (val == '') {
        return false
    }

    if (val == null) {
        return false
    }

    if (number) {
        if (isNaN(val)) {
            return false
        }
    }

    if (val == undefined) {
        return false
    }

    return true
}

function showToast(message = 'Something Wrong') {
    // Get the snackbar DIV
    var x = document.getElementById("snackbar");
    // Add the "show" class to DIV
    x.className = "show";
    x.innerHTML = message;
    // After 3 seconds, remove the show class from DIV
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 5000);
}

async function sleep(ms) {
    return await new Promise(resolve => setTimeout(resolve, ms));
}

function rounds(numb, digit = 1) {
    if (typeof digit != 'number') {
        return numb
    }

    let fix = numb.toFixed(digit)
    let float = parseFloat(fix)

    return float
}

const arry = {
    insert : function(arr1, arr2) {
        let arr = arr1.filter(x => arr2.includes(x))
        return arr
    },
    diff : function(arr1, arr2) {
        let arr =arr1.filter(x => !arr2.includes(x))
        return arr
    },
    symdiff : function(arr1, arr2){
        let arr = arr1.filter(x => !arr2.includes(x))
        .concat(arr2.filter(x => !arr1.includes(x)))

        return arr
    }
}

const calert = {
    success : function(message, button = 'OK'){
        Swal.fire({
            title: "TPS Says :",
            text: message,
            icon: "success",
            confirmButtonText: button
        });
    },
    error : function(message, button = 'Kembali'){
        Swal.fire({
            title: "TPS Says :",
            text: message,
            icon: "error",
            confirmButtonText: button
        });
    },
    info : function(message, button = 'OK'){
        Swal.fire({
            title: "TPS Says :",
            text: message,
            icon: "info",
            confirmButtonText: button
        });
    },
    warning : function(message, button = 'OK'){
        Swal.fire({
            title: "TPS Says :",
            text: message,
            icon: "warning",
            confirmButtonText: button
        });
    },
    toast: function(message, icon) {
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
    loading: function() {
        Swal.fire({
            title: 'Menunggu...',
            text: 'Proses sedang berlangusng...',
            showConfirmButton: false, // hilangin tombol OK
            showCancelButton: false,  // hilangin tombol Cancel (default-nya emang gak ada sih)
            allowOutsideClick: false, // biar gak bisa ditutup klik luar
            allowEscapeKey: false     // biar gak bisa ditutup pakai tombol Esc
        });
    },
    close : function() {
        Swal.close();
    }
}

function createSLUGX(text, deli = '-') {
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


function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function getRandomDigits(length) {
    let min = Math.pow(10, length - 1);
    let max = Math.pow(10, length) - 1;
    return getRandomInt(min, max);
}

function loadFormOnUpdate(model, data) {
    $.each(data, function(k,v){
        let lowstr = k.toLowerCase()
        let inputs = $(`#${model}-${lowstr}`)

        if (inputs.is('select')) {
            if (inputs.hasClass('select2-hidden-accessible')){
                let relation = inputs.attr(`data-text-${k}`)
                let htopt = createHTML.tag('option', data[relation], {value: v})
                inputs.html(htopt)
                inputs.val(v).change()
            } else {
                inputs.val(v)
            }
        } else {
            inputs.val(v)
        }
    })
}

document.addEventListener("DOMContentLoaded", function(event) {
})