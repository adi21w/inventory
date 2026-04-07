document.addEventListener("DOMContentLoaded", function(event) {
    $(document).on('click', '#btn-change-password, .btn-password-grid', async function(e){
        var button = $(this)
        button.buttonFn('loading')

        let pabID = $(this).attr('data-id')
        
        const { value: password } = await Swal.fire({
            title: `Change Password ?`,
            input: "password",
            inputLabel: "Password",
            inputPlaceholder: "Enter your password",
            inputAttributes: {
                maxlength: "15",
                autocapitalize: "off",
                autocorrect: "off"
            }
        });

        if (password) {
            Swal.fire({
                icon : 'info',
                title : 'Sedang mengirim data',
                text : 'Loading...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false
            })

            let param = {
                'csrf-param' : yii.getCsrfParam(),
                'csrf-token' : yii.getCsrfToken(),
                password
            }

            const url = parseInt(pabID) > 0 ? `/user/password` : $helper.path.create(`/user/password`, {id: pabID})

            $helper.ajaxCall({url, type: 'POST', data: param})
            .done((res) => {
                if (res.status) {
                    $helper.sweet.success(res.message)
                } else {
                    $helper.sweet.error(res.error)
                }
            })
            .always(() => button.buttonFn('reset'))
        }

        button.buttonFn('reset')
    })
})