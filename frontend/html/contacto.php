<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>
    <link rel="stylesheet" href="../css/contacto.css">
</head>

<body>
    <header>
        <h2 style="text-align: center;"> </h2>
        <a href="index.php">
            <div id="back-home" style="position: absolute; top: 10px; left: 10px;
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 5px 10px;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);">
                <img src="../images/logo-black.png" alt="">
            </div>
        </a>

    </header>
    <main>
        <section class="info-contacto">
            <h2>Informações de Contato</h2>
            <p>Telefone: +258 84 123 4567</p>
            <p>WhatsApp: +258 84 987 6543</p>
            <p>Endereço: Hulene, nº 123, Maputo, Moçambique</p>
        </section>
        <section class="mapa">
            <h2>Localização</h2>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15069.340456968622!2d32.5941449!3d-25.9534669!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1ee66912f09dc5ef%3A0xa2cf66d2c76c743b!2sGuilherme%20Guyer%20Station%2C%20Maputo!5e0!3m2!1sen!2smz!4v1699261234567!5m2!1sen!2smz">
            </iframe>
        </section>
        <section class="formulario">
            <h2>Envie-nos uma Mensagem</h2>
            <form action="processar_formulario.php" method="POST">
                <label for="assunto">Assunto:</label>
                <input type="text" id="assunto" name="assunto" required>

                <label for="mensagem">Mensagem:</label>
                <textarea id="mensagem" name="mensagem" rows="5" required></textarea>

                <button type="submit">Enviar</button>
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Pain Designer</p>
    </footer>
</body>

</html>