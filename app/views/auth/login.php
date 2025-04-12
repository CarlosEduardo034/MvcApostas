<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/APOSTAS_MVC_COMPLETO/public/css/login.css">
    <title>Document</title>
</head>

<body>
    <main>
        <section class="container01">
            <div class="containerCadratro">
                <h2>Conecte-se</h2>
                <form action="/apostas_mvc_completo/public/index.php?action=do_login" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div class="user-box">
                        <input type="text" name="email" required="">
                        <label>Email</label>
                    </div>
                    <div class="user-box">
                        <input type="password" name="senha" required="">
                        <label>Senha</label>
                    </div>
                    <div class="user-box">
                        <input type="submit" name="submit" id="submit" value="Entrar">
                    </div>
                </form>
            </div>
        </section>
        <section class="container02"></section>
    </main>
</body>

</html>