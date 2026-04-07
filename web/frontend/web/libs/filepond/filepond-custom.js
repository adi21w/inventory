// Filepond: With Validation Excel
FilePond.create( document.querySelector('.with-validation-filepond-excel'), { 
    storeAsFile: true,
    allowMultiple: false,
    allowFileEncode: false,
    server: null,
    required: true,
    credits: false,
    acceptedFileTypes: [
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ],
    fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
        // Do custom type detection here and return with promise
        resolve(type);
    })
})
// Filepond: With Validation Produk
FilePond.create( document.querySelector('.with-validation-filepond-produk'), { 
    storeAsFile: true,
    allowMultiple: false,
    allowFileEncode: false,
    allowImagePreview: true, 
    allowImageFilter: false,
    allowImageExifOrientation: false,
    allowImageCrop: false,
    server: null,
    required: true,
    credits: false,
    acceptedFileTypes: ['image/png','image/jpg','image/jpeg', 'image/webp', 'image/avif'],
    fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
        // Do custom type detection here and return with promise
        resolve(type);
    })
})