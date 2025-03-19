document.querySelector("#verificarEmail").addEventListener("click", function() {
    let email = document.querySelector("#email").value;

    fetch("ajax/usuario_ajax.php", {
        method: "POST",
        body: new URLSearchParams({ email: email }),
        headers: { "Content-Type": "application/x-www-form-urlencoded" }
    })
    .then(response => response.json())
    .then(data => alert(data ? "Usuário encontrado!" : "Usuário não encontrado!"));
});
