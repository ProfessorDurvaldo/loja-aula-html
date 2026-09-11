<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Laticínios Bela Vaca - Atendimento</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #e8f5e9;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .chat {
            background: #fff;
            width: 100%;
            max-width: 480px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,.15);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 80vh;
        }
        .cabecalho {
            background: #2e7d32;
            color: #fff;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .cabecalho h1 { font-size: 18px; }
        .cabecalho a {
            color: #fff;
            font-size: 12px;
            text-decoration: none;
            background: rgba(255,255,255,.2);
            padding: 5px 10px;
            border-radius: 6px;
        }
        .mensagens {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .msg {
            max-width: 80%;
            padding: 10px 14px;
            border-radius: 14px;
            line-height: 1.4;
            font-size: 14px;
            white-space: pre-wrap;
        }
        .msg.user {
            background: #2e7d32;
            color: #fff;
            align-self: flex-end;
            border-bottom-right-radius: 4px;
        }
        .msg.bot {
            background: #f1f1f1;
            color: #333;
            align-self: flex-start;
            border-bottom-left-radius: 4px;
        }
        .vazio {
            color: #999;
            text-align: center;
            margin: auto;
            font-size: 14px;
        }
        form {
            display: flex;
            gap: 8px;
            padding: 14px;
            border-top: 1px solid #eee;
        }
        input[type="text"] {
            flex: 1;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 20px;
            font-size: 14px;
            outline: none;
        }
        button {
            background: #2e7d32;
            color: #fff;
            border: none;
            width: 46px;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
        }
        button:hover { background: #1b5e20; }
    </style>
</head>
<body>
    <div class="chat">
        <div class="cabecalho">
            <h1>🐄 Bela Vaca — Atendimento</h1>
            <a href="<?= site_url('chatbot/limpar') ?>">Limpar</a>
        </div>

        <div class="mensagens" id="mensagens">
            <?php if (empty($historico)): ?>
                <p class="vazio">Olá! Pergunte algo sobre nossos laticínios 🧀</p>
            <?php else: ?>
                <?php foreach ($historico as $msg): ?>
                    <div class="msg <?= $msg['role'] === 'user' ? 'user' : 'bot' ?>">
                        <?= $msg['content'] ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <form action="<?= site_url('chatbot/enviar') ?>" method="post">
            <input type="text" name="mensagem" placeholder="Digite sua mensagem..."
                   autocomplete="off" required autofocus>
            <button type="submit">➤</button>
        </form>
    </div>

    <script>
        // Rola a conversa pro final automaticamente
        var caixa = document.getElementById('mensagens');
        caixa.scrollTop = caixa.scrollHeight;
    </script>
</body>
</html>
