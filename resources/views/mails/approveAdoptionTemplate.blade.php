<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprovação de Adoção de Pet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        p {
            color: #666;
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #999;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Aprovação de Adoção de Pet</h1>
        <p>Olá {{$adoption->name}},</p>
        <p>Ficamos felizes em informar que sua solicitação para adoção de {{$pet->name}} foi aprovada!</p>
        <p>Você pode entrar em contato conosco para agendar a retirada do seu novo companheiro. Lembre-se de trazer os documentos necessários e seguir todas as orientações para garantir um processo tranquilo.</p>
        <p>Se tiver alguma dúvida, não hesite em nos contatar.</p>
        <div class="footer">
            <p>Atenciosamente,<br>Equipe de Adoção de Pets</p>
        </div>
    </div>
</body>
</html>
