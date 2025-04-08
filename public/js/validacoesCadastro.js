document.addEventListener("DOMContentLoaded", () => {
    let emailValido = true;
    let cpfValido = true;
    const emailInput = document.getElementById("email");
    const cpfInput = document.getElementById("cpf");
    const emailMsg = document.getElementById("email-msg");
    const cpfMsg = document.getElementById("cpf-msg");

    emailInput.addEventListener("blur", () => {
        const email = emailInput.value;

        fetch("/apostas_mvc_completo/public/verifica.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `email=${encodeURIComponent(email)}`
        })
            .then(res => res.text())
            .then(res => {
                if (res === "existe") {
                    emailMsg.textContent = "Este e-mail já está em uso.";
                    emailValido = false;
                } else {
                    emailMsg.textContent = "";
                    emailValido = true;
                }
            });
    });

    cpfInput.addEventListener("blur", () => {
        const cpf = cpfInput.value;
    
        fetch("/apostas_mvc_completo/public/verifica.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `cpf=${encodeURIComponent(cpf)}`
        })
            .then(res => res.text())
            .then(res => {
                if (res === "existe") {
                    cpfMsg.textContent = "Este CPF já está em uso."; // Mensagem visual
                    cpfValido = false;
                } else {
                    cpfMsg.textContent = "";
                    cpfValido = true;
                }
            });
    });
});

document.getElementById('submit').addEventListener('click', function (event) {
    const nome = document.getElementById('nome').value.trim();
    const email = document.getElementById('email').value.trim();
    const telefone = document.getElementById('telefone').value.trim();
    const dataNascimento = document.getElementById('data_nascimento').value;
    const cpf = document.getElementById('cpf').value.trim();
    const senha = document.getElementById('senha').value;

    const emailMsg = document.getElementById("email-msg").textContent;
    const cpfMsg = document.getElementById("cpf-msg").textContent;

    // Verificações visuais
    if (emailMsg !== "") {
        alert("Corrija o e-mail antes de prosseguir.");
        event.preventDefault();
        return;
    }

    if (cpfMsg !== "") {
        alert("Corrija o CPF antes de prosseguir.");
        event.preventDefault();
        return;
    }

    // Demais validações já existentes
    if (!nome) {
        alert("Por favor, preencha o nome.");
        event.preventDefault();
        return;
    }

    if (nome.length > 20) {
        alert("O nome deve ter no máximo 20 caracteres.");
        event.preventDefault();
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert("Por favor, insira um e-mail válido.");
        event.preventDefault();
        return;
    }

    const telRegex = /^\d{10,11}$/;
    if (!telRegex.test(telefone)) {
        alert("O telefone deve conter apenas números (com DDD) e ter 10 ou 11 dígitos.");
        event.preventDefault();
        return;
    }

    if (!dataNascimento) {
        alert("Por favor, preencha a data de nascimento.");
        event.preventDefault();
        return;
    }

    const nascimento = new Date(dataNascimento);
    const hoje = new Date();
    const idadeMinima = new Date();
    idadeMinima.setFullYear(hoje.getFullYear() - 18);
    const idadeMaxima = new Date("1900-01-01");

    if (nascimento > idadeMinima) {
        alert("Você precisa ter pelo menos 18 anos para se cadastrar.");
        event.preventDefault();
        return;
    }

    if (nascimento < idadeMaxima) {
        alert("Data de nascimento inválida. Data muito antiga.");
        event.preventDefault();
        return;
    }

    const cpfRegex = /^\d{11}$/;
    if (!cpfRegex.test(cpf)) {
        alert("O CPF deve conter exatamente 11 números.");
        event.preventDefault();
        return;
    }

    if (senha.length < 6) {
        alert("A senha deve ter pelo menos 6 caracteres.");
        event.preventDefault();
        return;
    }
});
