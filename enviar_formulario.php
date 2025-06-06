<?php
header('Content-Type: application/json; charset=UTF-8');

$response = ['status' => 'error', 'message' => 'Ocorreu um erro inesperado.'];

// Cores da paleta (para fácil referência)
$corFundoPrincipal = '#FFFBF6'; // custom-off-white
$corHeaderBg = '#FDF6E8'; // custom-light-beige (NOVO para fundo do header)
$corHeaderText = '#3B84B7'; // custom-steel-blue (NOVO para texto do header e títulos)
$corFooterBg = '#3B84B7'; // custom-steel-blue (para fundo do footer)
$corFooterText = '#FFFFFF'; // para texto do footer
$corContainer = '#FFFFFF';
$corTextoPrincipal = '#333333';
$corDestaque = '#FBA57A'; // custom-coral
$corBorda = '#D2E0E4'; // custom-light-blue-gray
$corTextoSecundario = '#555555';
$corBegeClaroMensagem = '#FDF6E8'; // custom-light-beige (mantido para a seção da mensagem)


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $destinatario = "edipo1998@gmail.com";
    $assunto = "Nova Cotação de Viagem via Site - Trippin' Club"; 
    $urlLogo = "https://brown-stork-488794.hostingersite.com/assets/trippin_club_logo_sem_fundo_novo.png";

    $nome = strip_tags(trim($_POST["name"]));
    $email_cliente = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $telefone = strip_tags(trim($_POST["phone"]));
    $destino_viagem = strip_tags(trim($_POST["destination"]));
    $datas_viagem = strip_tags(trim($_POST["travel_dates"]));
    $num_viajantes = strip_tags(trim($_POST["num_travelers"]));
    $mensagem_cliente = nl2br(htmlspecialchars(trim($_POST["message"])));

    // Validação
    if (empty($nome) || !filter_var($email_cliente, FILTER_VALIDATE_EMAIL) || empty($destino_viagem)) {
        $response['message'] = 'Por favor, preencha todos os campos obrigatórios (Nome, E-mail e Destino) corretamente.';
        echo json_encode($response);
        exit; 
    }

    $corpo_email = "
    <!DOCTYPE html>
    <html lang='pt-BR'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>$assunto</title>
        <style>
            body { margin: 0; padding: 0; background-color: $corFundoPrincipal; font-family: 'Inter', Arial, sans-serif; }
            .email-wrapper { width: 100%; background-color: $corFundoPrincipal; padding: 20px 0; }
            .email-container { width: 90%; max-width: 600px; margin: 0 auto; background-color: $corContainer; border: 1px solid $corBorda; border-radius: 8px; overflow: hidden; }
            
            .email-header { background-color: $corHeaderBg; color: $corHeaderText; padding: 20px; text-align: center; }
            .email-header img { max-width: 180px; height: auto; margin-bottom: 10px; }
            .email-header h1 { margin: 0; font-size: 24px; color: $corHeaderText !important; }
            
            .email-body { padding: 25px 30px; color: $corTextoPrincipal; font-size: 16px; line-height: 1.6; }
            .email-body h2 { color: $corHeaderText; font-size: 20px; margin-top: 0; margin-bottom: 15px; }
            .email-body p { margin: 0 0 10px 0; }
            .email-body .label { font-weight: bold; color: $corHeaderText; }
            .email-body .data { margin-bottom: 15px; padding-left: 10px; border-left: 3px solid $corDestaque; }
            .email-body .message-section { margin-top: 20px; padding: 15px; background-color: $corBegeClaroMensagem; border-radius: 5px; }
            
            .email-footer { background-color: $corFooterBg; color: $corFooterText; padding: 15px; text-align: center; font-size: 12px; }
            .email-footer a { color: $corDestaque; text-decoration: none; }
            strong { color: $corHeaderText; } /* Para dar destaque aos títulos dos campos e outros 'strong's */
        </style>
    </head>
    <body>
        <div class='email-wrapper'>
            <div class='email-container'>
                <div class='email-header'>
                    <img src='$urlLogo' alt='Logo Trippin&apos; Club'>
                    <h1>Nova Solicitação de Cotação</h1>
                </div>
                <div class='email-body'>
                    <h2>Olá, Equipe Trippin' Club!</h2>
                    <p>Você recebeu uma nova solicitação de cotação através do site. Seguem os detalhes:</p>
                    
                    <p class='data'><span class='label'>Nome:</span> $nome</p>
                    <p class='data'><span class='label'>E-mail:</span> <a href='mailto:$email_cliente' style='color: $corDestaque;'>$email_cliente</a></p>
                    <p class='data'><span class='label'>Telefone:</span> $telefone</p>
                    
                    <h2 style='margin-top: 25px;'>Detalhes da Viagem:</h2>
                    <p class='data'><span class='label'>Destino:</span> $destino_viagem</p>
                    <p class='data'><span class='label'>Datas Aproximadas:</span> $datas_viagem</p>
                    <p class='data'><span class='label'>Número de Viajantes:</span> $num_viajantes</p>
                    
                    <div class='message-section'>
                        <p class='label'>Mensagem Adicional:</p>
                        <p>$mensagem_cliente</p>
                    </div>
                </div>
                <div class='email-footer'>
                    <p>&copy; " . date("Y") . " Trippin' Club. Todos os direitos reservados.</p>
                    <p>Este é um e-mail automático enviado através do formulário de cotação do site.</p>
                </div>
            </div>
        </div>
    </body>
    </html>
    ";

    $remetente_nome = "Trippin' Club Cotações"; 
    $remetente_email = "nao-responda@" . preg_replace('(^www\.)', '', $_SERVER['SERVER_NAME']);
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: \"$remetente_nome\" <$remetente_email>\r\n";
    $headers .= "Reply-To: \"$nome\" <$email_cliente>\r\n"; 
    $headers .= "X-Mailer: PHP/" . phpversion();

    if (mail($destinatario, "=?UTF-8?B?".base64_encode($assunto)."?=", $corpo_email, $headers)) { 
        $response['status'] = 'success';
        $response['message'] = 'Sua solicitação foi enviada com sucesso! Em breve, entraremos em contato.';
    } else {
        error_log("Falha ao enviar e-mail de cotação. Destinatário: $destinatario, Assunto: $assunto, Headers: $headers");
        $response['message'] = 'Houve uma falha no servidor ao tentar enviar o e-mail. Por favor, tente novamente mais tarde ou entre em contato por outro canal.';
    }
} else {
    $response['message'] = 'Método de requisição inválido.';
}

echo json_encode($response);
exit;
?>
