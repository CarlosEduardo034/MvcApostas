<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/APOSTAS_MVC_COMPLETO/public/css/cadastro.css">
    <title>Document</title>
</head>

<body>
    <main>
        <section class="container01">
            <div class="containerCadratro">
                <h2>Cadastre-se</h2>
                <form action="index.php?action=do_register" method="POST">
                    <div class="user-box">
                        <input type="text" name="nome" id="nome" required="">
                        <label>Nome</label>
                    </div>
                    <div class="user-box">
                        <input type="text" name="email" id="email" required="">
                        <label>Email</label>
                        <span id="email-msg" style="color:red; font-size: 0.9em; padding: 20px;"></span>
                    </div>
                    <div class="user-box">
                        <input type="text" name="telefone" id="telefone" required="">
                        <label>Telefone</label>
                    </div>
                    <div class="user-box">
                        <p class="data">Data de nascimento</p>
                        <input type="date" name="data_nascimento" id="data_nascimento" required="">
                    </div>
                    <div class="user-box">
                        <input type="text" name="cpf" id="cpf" required="">
                        <label>CPF</label>
                    </div>
                    <div class="user-box">
                        <input type="password" name="senha" id="senha" required="">
                        <label>Senha</label>
                    </div>
                    <div class="user-box">
                        <input type="submit" name="submit" id="submit" value="Cadastrar">
                    </div>
                </form>
            </div>
        </section>
        <section class="container02"></section>
    </main>
    <script src="/apostas_mvc_completo/public/js/validacoesCadastro.js"></script>
</body>

</html>