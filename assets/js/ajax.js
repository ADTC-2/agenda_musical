function fazerLogin(email, senha) {
    $.ajax({
        url: '/agenda_musical/ajax/usuario_ajax.php',
        type: 'POST',
        data: { email: email, senha: senha },
        success: function(response) {
            if (response.success) {
                window.location.href = '/agenda_musical/public/index.php';
            } else {
                alert(response.message);
            }
        }
    });
}