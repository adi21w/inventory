function onLoad() {
    let url = url2Obj()
    if (url.hasOwnProperty('q')) {
        const bsCollapse = new bootstrap.Collapse(`#${url.q}`, {
            toggle: true
        })

        return true
    }

    return
}

window.addEventListener("load", function () {
    onLoad()
});