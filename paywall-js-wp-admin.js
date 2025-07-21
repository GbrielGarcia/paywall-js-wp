jQuery(document).ready(function($) {
    function toggleColorFields() {
        var effect = $("select[name='paywall_js_wp_options[effect]']").val();
        if (effect === 'gradient') {
            $('#paywall-js-wp-gradient-from').closest('tr').show();
            $('#paywall-js-wp-gradient-to').closest('tr').show();
            $('#paywall-js-wp-color').closest('tr').hide();
        } else if (effect === 'solid') {
            $('#paywall-js-wp-gradient-from').closest('tr').hide();
            $('#paywall-js-wp-gradient-to').closest('tr').hide();
            $('#paywall-js-wp-color').closest('tr').show();
        } else {
            $('#paywall-js-wp-gradient-from').closest('tr').hide();
            $('#paywall-js-wp-gradient-to').closest('tr').hide();
            $('#paywall-js-wp-color').closest('tr').hide();
        }
    }

    function togglePaywallFields() {
        var enabled = $("input[name='paywall_js_wp_options[enabled]']").is(':checked');
        // Oculta/muestra todos los campos excepto el de habilitar
        var $trs = $('#paywall-js-wp-form table.form-table tr');
        $trs.each(function() {
            var $th = $(this).find('th');
            if ($th.length && $th.text().indexOf('Habilitar Paywall') === -1) {
                if (enabled) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }
        });
        // Manejar el atributo required del campo de fecha
        var $dueDate = $('#paywall-js-wp-due-date');
        if (enabled) {
            $dueDate.attr('required', 'required');
        } else {
            $dueDate.removeAttr('required');
        }
    }

    toggleColorFields();
    togglePaywallFields();
    $("select[name='paywall_js_wp_options[effect]']").on('change', toggleColorFields);
    $("input[name='paywall_js_wp_options[enabled]']").on('change', function() {
        togglePaywallFields();
    });

    // Validación de fecha solo si el paywall está activado
    $('#paywall-js-wp-form').on('submit', function(e) {
        var enabled = $("input[name='paywall_js_wp_options[enabled]']").is(':checked');
        var dueDate = $('#paywall-js-wp-due-date').val();
        if (enabled && !dueDate) {
            alert('Por favor, selecciona la fecha de vencimiento para activar el paywall.');
            $('#paywall-js-wp-due-date').focus();
            e.preventDefault();
            return false;
        }
        return true;
    });
}); 