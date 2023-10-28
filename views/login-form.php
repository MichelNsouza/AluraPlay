<?php $this->layout('layout'); ?>

<main class="container">
    <form class="container__formulario" method="post">
        <h2 class="formulario__titulo">Efetue login</h2>
            <div class="formulario__campo">
                <label class="campo__etiqueta" for="usuario">E-mail</label>
                <input name="email" type="email" class="campo__escrita" required
                    placeholder="userTest@test.com" id='usuario' />
            </div>

            <div class="formulario__campo">
                <label class="campo__etiqueta" for="senha">Senha</label>
                <input type="password" name="password" class="campo__escrita" required placeholder="123456789"
                    id='senha' />
            </div>

            <input class="formulario__botao" type="submit" value="Entrar" />
    </form>

</main>