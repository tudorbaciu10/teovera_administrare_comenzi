/* comanda.js — interfața vânzătoarei */

document.addEventListener('DOMContentLoaded', function () {

    // Actualizează numărul de produse selectate
    function updateSelectedCount() {
        var count = 0;
        document.querySelectorAll('.qty-input').forEach(function (input) {
            var val = parseFloat(input.value);
            if (!isNaN(val) && val > 0) count++;
        });
        var badge = document.getElementById('selectedCount');
        if (badge) badge.textContent = count;
    }

    document.querySelectorAll('.qty-input').forEach(function (input) {
        input.addEventListener('input', updateSelectedCount);
    });

    updateSelectedCount();

});
