/* admin.js — panou Teovera */

$(function () {

    // ── DataTables: tabel comenzi ─────────────────────────
    var ordersTable = null;

    if ($('#ordersTable').length) {
        ordersTable = $('#ordersTable').DataTable({
            pageLength: 25,
            // col 0 = checkbox, col 1 = ID → sortare implicită după ID desc
            order: [[1, 'desc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/ro.json',
                emptyTable: 'Nu există comenzi pentru filtrele selectate.',
                search: 'Caută:',
                lengthMenu: 'Arată _MENU_ rânduri',
                info: '_START_–_END_ din _TOTAL_ comenzi',
                infoEmpty: '0 comenzi',
                paginate: { previous: '‹', next: '›' }
            },
            columnDefs: [
                { orderable: false, searchable: false, targets: 0 }, // checkbox
                { orderable: false, targets: -1 }                     // acțiuni
            ]
        });
    }

    // ── DataTables: tabele simple (CRUD) ──────────────────
    if ($('.simple-table').length) {
        $('.simple-table').DataTable({
            pageLength: 25,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/ro.json',
                search: 'Caută:',
                lengthMenu: 'Arată _MENU_ rânduri',
                paginate: { previous: '‹', next: '›' }
            },
            columnDefs: [{ orderable: false, targets: -1 }]
        });
    }

    // ── Selectare masivă (checkbox + click rând) ─────────
    function updateRowHighlight() {
        $('.order-row').each(function () {
            $(this).toggleClass('row-selected', $(this).find('.order-check').prop('checked'));
        });
    }

    function updateBulkBar() {
        var n = $('.order-check:checked').length;
        $('#bulkCount').text(n + ' selectate');
        if (n > 0) {
            $('#bulkBar').removeClass('is-empty');
        } else {
            $('#bulkBar').addClass('is-empty');
        }
        updateRowHighlight();
    }

    // Click pe orice celulă a rândului (exclusiv coloana checkbox și acțiuni)
    $(document).on('click', '.order-row td:not(.col-check):not(.col-actions)', function (e) {
        if ($(e.target).is('a, button, input, svg, path, use') || $(e.target).closest('a, button').length) return;
        var $cb = $(this).closest('tr').find('.order-check');
        $cb.prop('checked', !$cb.prop('checked')).trigger('change');
    });

    // "Selectează tot" — afectează doar rândurile vizibile (filtrate)
    $('#selectAll').on('change', function () {
        var checked = this.checked;
        if (ordersTable) {
            ordersTable.rows({ search: 'applied' }).nodes().to$()
                .find('.order-check').prop('checked', checked);
        } else {
            $('.order-check').prop('checked', checked);
        }
        updateBulkBar();
    });

    // Actualizare stare "selectează tot" la click individual
    $(document).on('change', '.order-check', function () {
        updateBulkBar();
        var total   = ordersTable
            ? ordersTable.rows({ search: 'applied' }).nodes().to$().find('.order-check').length
            : $('.order-check').length;
        var checked = ordersTable
            ? ordersTable.rows({ search: 'applied' }).nodes().to$().find('.order-check:checked').length
            : $('.order-check:checked').length;
        var $sa = $('#selectAll')[0];
        if ($sa) {
            $sa.indeterminate = checked > 0 && checked < total;
            $sa.checked       = checked === total && total > 0;
        }
    });

    // Resetează checkboxuri la re-filtrare în DataTables
    if (ordersTable) {
        ordersTable.on('draw', function () {
            updateBulkBar();
            var $sa = $('#selectAll')[0];
            if ($sa) { $sa.checked = false; $sa.indeterminate = false; }
        });
    }

    // Buton "Printează selectate"
    $('#btnPrintSelected').on('click', function () {
        var ids = $('.order-check:checked').map(function () { return this.value; }).get();
        if (!ids.length) return;
        window.open('/print/orders?ids=' + ids.join(','), '_blank');
    });

    // ── Butoane dată rapidă ───────────────────────────────
    function isoDate(d) {
        return d.toISOString().split('T')[0];
    }

    $('#btnAzi').on('click', function () {
        $('#filterData').val(isoDate(new Date()));
        $('#filtersForm').submit();
    });

    $('#btnMaine').on('click', function () {
        var t = new Date();
        t.setDate(t.getDate() + 1);
        $('#filterData').val(isoDate(t));
        $('#filtersForm').submit();
    });

    // ── Tooltip produse (JS, position: fixed) ─────────────
    var $tip = $('<div class="prod-tip-floating"></div>').appendTo('body');

    $(document).on('mouseenter', '.cell-produse', function () {
        var products = $(this).data('products');
        if (!products || !products.length) return;

        var html = '<div class="prod-tip-header">' + products.length + ' produse</div>';
        products.forEach(function (p) {
            html += '<div class="prod-tip-row">'
                  + '<span>' + $('<span>').text(p.n).html() + '</span>'
                  + '<strong>' + $('<span>').text(p.q + ' ' + p.u).html() + '</strong>'
                  + '</div>';
        });
        $tip.html(html);

        var rect = this.getBoundingClientRect();
        var tipW = 240;
        var left = rect.left;
        if (left + tipW > window.innerWidth - 12) {
            left = window.innerWidth - tipW - 12;
        }
        var top = rect.bottom + 5;

        $tip.css({ top: top, left: left }).show();
    }).on('mouseleave', '.cell-produse', function () {
        $tip.hide();
    });

    // Ascunde tooltip la scroll (poziționat fixed, dar previne desync)
    $(window).on('scroll', function () { $tip.hide(); });

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
