$('.modal').on('shown.bs.modal', function() {
    $(this).find('input[autofocus]').focus();
});

function showLoading() {
    swal.fire({
        text: 'Loading...',
        imageUrl: 'custom/img/Spinner-1s-151px.svg',
        imageWidth: 100,
        imageHeight: 100,
        // allowOutsideClick: false,
        showConfirmButton: false
    });
}

function closeLoading() {
    swal.close();
}