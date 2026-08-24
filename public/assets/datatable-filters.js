function applyDtFilterStyling() {
    $('.dtsp-collapseAll').click();
    $('.dtsp-paneButton.dtsp-nameButton.dtsp-disabledButton').removeClass('dtsp-paneButton dtsp-nameButton dtsp-disabledButton');
    $('.dtsp-paneButton.dtsp-countButton').removeClass('dtsp-paneButton dtsp-countButton');
    $('.dtsp-collapseAll').text('Ocultar Filtros');
    $('.dtsp-showAll').text('Mostrar Filtros');
    $('.dtsp-titleRow').addClass('flex flex-row items-center');
    $('.dtsp-titleRow > button').addClass('text-black py-2 px-4 mr-2 mb-2 transition ease-in-out duration-150 shadow-md').each(function () {
        if ($(this).is('.dtsp-disabledButton, :disabled')) {
            $(this).addClass('bg-gray-300 border-gray-400 text-gray-500 cursor-not-allowed');
        } else {
            $(this).addClass('bg-blue-200 hover:bg-blue-300 focus:bg-blue-300 border-blue-300 hover:shadow-lg focus:shadow-lg');

            $(this).on('focus', function () {
                $(this).addClass('ring ring-blue-300 ring-offset-2 ring-opacity-50');
            }).on('blur', function () {
                $(this).removeClass('ring ring-blue-300 ring-offset-2 ring-opacity-50');
            });
        }
    });
}
