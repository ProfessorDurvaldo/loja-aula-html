<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chatbot extends CI_Controller {

    private $token = 'hf_COLE_SEU_TOKEN_AQUI';
    private $modelo = 'meta-llama/Llama-3.1-8B-Instruct';

    private function system_prompt() {
        return "Você é o atendente virtual da Laticínios Bela Vaca, uma loja de "
             . "laticínios artesanais em Montes Claros, MG. Seja simpático, breve e "
             . "sempre útil. Informações da loja:\n"
             . "- Produtos: queijo minas, queijo coalho, mussarela, requeijão, "
             . "iogurte natural, manteiga, doce de leite e leite fresco.\n"
             . "- Horário: segunda a sábado, das 7h às 18h.\n"
             . "- Entrega: fazemos entrega em Montes Claros para compras acima de R\$ 50.\n"
             . "- Pagamento: dinheiro, cartão e Pix.\n"
             . "Se perguntarem algo que não sabe, diga que vai verificar com a gerência. "
             . "Responda sempre em português.";
    }

    public function index() {
        $historico = $this->session->userdata('chat') ?? [];
        $data['historico'] = $historico;
        $this->load->view('chatbot/index', $data);
    }

    public function enviar() {
        $pergunta = $this->input->post('mensagem');
        $historico = $this->session->userdata('chat') ?? [];
        $historico[] = ['role' => 'user', 'content' => $pergunta];

        $mensagens = [
            ['role' => 'system', 'content' => $this->system_prompt()]
        ];
        foreach (array_slice($historico, -10) as $msg) {
            $mensagens[] = $msg;
        }

        $resposta = $this->perguntar_ia($mensagens);

        $historico[] = ['role' => 'assistant', 'content' => $resposta];
        $this->session->set_userdata('chat', $historico);

        redirect('chatbot');
    }

    public function limpar() {
        $this->session->unset_userdata('chat');
        redirect('chatbot');
    }

    private function perguntar_ia($mensagens) {
        $url = 'https://router.huggingface.co/v1/chat/completions';

        $corpo = json_encode([
            'model'    => $this->modelo,
            'messages' => $mensagens
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $corpo);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json'
        ]);

        $retorno = curl_exec($ch);
        curl_close($ch);

        $dados = json_decode($retorno);

        if (isset($dados->choices[0]->message->content)) {
            return $dados->choices[0]->message->content;
        }

        return 'Ops, não consegui responder agora.';
    }
}
