/* admin.js — panou Teovera */

$(function () {

    // ── DataTables ────────────────────────────────────────
    if ($('#ordersTable').length) {
        $('#ordersTable').DataTable({
            pageLength: 25,
            order: [[0, 'desc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/ro.json',
                emptyTable: 'Nu există comenzi pentru filtrele selectate.'
            },
            columnDefs: [
                { orderable: false, targets: -1 }
            ]
        });
    }

    if ($('.simple-table').length) {
        $('.simple-table').DataTable({
            pageLength: 25,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/ro.json'
            },
            columnDefs: [
                { orderable: false, targets: -1 }
            ]
        });
    }

    // ── Copiere link în clipboard ─────────────────────────
    $(document).on('click', '#copyLinkBtn', function () {
        var url = $(this).data('url');
        if (navigator.clipboard && url) {
            navigator.clipboard.writeText(url).then(function () {
                $('#copyFeedback').fadeIn(200).delay(2000).fadeOut(400);
            });
        }
    });

    // ── Confirmare ștergere ───────────────────────────────
    $(document).on('submit', '.form-delete', function (e) {
        if (!confirm($(this).data('confirm') || 'Ești sigur?')) {
            e.preventDefault();
        }
    });

    // ── Polling comenzi noi (la fiecare 30s) ─────────────
    if ($('#newOrdersBadge').length) {
        setInterval(function () {
            $.get('/orders/poll', function (data) {
                if (data.count > 0) {
                    $('#newOrdersBadge').text(data.count).show();
                } else {
                    $('#newOrdersBadge').hide();
                }
            });
        }, 30000);
    }

});
