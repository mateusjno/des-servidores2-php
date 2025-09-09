<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sala extends CI_Controller
{

    /*
     * Validação dos tipos de retornos nas validações (Código de erro)
     * 1 - Operação realizada no banco de dados com sucesso (Inserção, Alteração, Consulta ou Exclusão)
     * 2 - Conteúdo passado nulo ou vazio
     * 3 - Conteúdo zerado
     * 4 - Conteúdo não inteiro
     * 5 - Conteúdo não é um texto
     * 6 - Data em formato inválido
     * 7 - Hora em formato inválido
     * * 99 - Parâmetros passados do front não correspondem ao método
     */

    private $codigo;
    private $descricao;
    private $andar;
    private $capacidade;
    private $estatus;


    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function getAndar()
    {
        return $this->andar;
    }

    public function getCapacidade()
    {
        return $this->capacidade;
    }

    public function getEstatus()
    {
        return $this->estatus;
    }

    // Setters dos atributos
    public function setCodigo($codigoFront)
    {
        $this->codigo = $codigoFront;
    }

    public function setDescricao($descricaoFront)
    {
        $this->descricao = $descricaoFront;
    }

    public function setAndar($andarFront)
    {
        $this->andar = $andarFront;
    }

    public function setCapacidade($capacidadeFront)
    {
        $this->capacidade = $capacidadeFront;
    }

    public function setEstatus($estatusFront)
    {
        $this->estatus = $estatusFront;
    }

    public function inserir()
    {

        $erros = [];
        $sucesso = false;

        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            $lista = [
                "codigo" => '0',
                "descricao" => '0',
                "andar" => '0',
                "capacidade" => '0'
            ];

            if (verificarParam($resultado, $lista) != 1) {

                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no FrontEnd.'];
            } else {

                $retornoCodigo = validarDados($resultado->codigo, 'int', true);
                $retornoDescricao = validarDados($resultado->descricao, 'string', true);
                $retornoAndar = validarDados($resultado->andar, 'int', true);
                $retornoCapacidade = validarDados($resultado->capacidade, 'int', true);

                if ($retornoCodigo['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCodigo['codigoHelper'],
                        'campo' => 'Codigo',
                        'msg' => $retornoCodigo['msg']
                    ];
                }

                if ($retornoDescricao['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoDescricao['codigoHelper'],
                        'campo' => 'Descrição',
                        'msg' => $retornoDescricao['msg']
                    ];
                }

                if ($retornoAndar['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoAndar['codigoHelper'],
                        'campo' => 'Andar',
                        'msg' => $retornoAndar['msg']
                    ];
                }

                if ($retornoCapacidade['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCapacidade['codigoHelper'],
                        'campo' => 'Capacidade',
                        'msg' => $retornoCapacidade['msg']
                    ];
                }


                if (empty($erros)) {
                    $this->setCodigo($resultado->codigo);
                    $this->setDescricao($resultado->descricao);
                    $this->setAndar($resultado->andar);
                    $this->setCapacidade($resultado->capacidade);

                    $this->load->model('M_sala');
                    $resBanco = $this->M_sala->inserir(
                        $this->getCodigo(),
                        $this->getDescricao(),
                        $this->getAndar(),
                        $this->getCapacidade()
                    );

                    if ($resBanco['codigo'] == 1) {
                        $sucesso = true;
                    } else {

                        $erros[] = [
                            'codigo' => $resBanco['codigo'],
                            'msg' => $resBanco['msg']
                        ];
                    }
                }
            }
        } catch (Exception $e) {
            $erros[] = ['codigo' => 0, 'msg' => 'Erro inesperado: ' . $e->getMessage()];
        }


        if ($sucesso === true) {
            $retorno = ['sucesso' => $sucesso, 'msg' => 'Sala cadastrada corretamente.'];
        } else {
            $retorno = ['sucesso' => $sucesso, 'erros' => $erros];
        }

        echo json_encode($retorno);
    }

    public function consultar()
    {
        // Atributos para controlar o status de nosso método
        $erros = [];
        $sucesso = false;

        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
            $lista = [
                "codigo" => '0',
                "descricao" => '0',
                "andar" => '0',
                "capacidade" => '0'
            ];

            if (verificarParam($resultado, $lista) != 1) {
                // Validar vindos de forma correta do frontend (Helper)
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistem ou incorretos no FrontEnd.'];
            } else {
                // Validar campos quanto ao tipo de dado e tamanho (Helper)
                $retornoCodigo = validarDadosConsulta($resultado->codigo, 'int');
                $retornoDescricao = validarDadosConsulta($resultado->descricao, 'string');
                $retornoAndar = validarDadosConsulta($resultado->andar, 'int');
                $retornoCapacidade = validarDadosConsulta($resultado->capacidade, 'int');

                if ($retornoCodigo['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCodigo['codigoHelper'],
                        'campo' => 'Código',
                        'msg' => $retornoCodigo['msg']
                    ];
                }

                if ($retornoDescricao['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoDescricao['codigoHelper'],
                        'campo' => 'Descrição',
                        'msg' => $retornoDescricao['msg']
                    ];
                }

                if ($retornoAndar['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoAndar['codigoHelper'],
                        'campo' => 'Andar',
                        'msg' => $retornoAndar['msg']
                    ];
                }

                if ($retornoCapacidade['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCapacidade['codigoHelper'],
                        'campo' => 'Capacidade',
                        'msg' => $retornoCapacidade['msg']
                    ];
                }

                // Se não encontrar erros
                if (empty($erros)) {
                    $this->setCodigo($resultado->codigo);
                    $this->setDescricao($resultado->descricao);
                    $this->setAndar($resultado->andar);
                    $this->setCapacidade($resultado->capacidade);

                    $this->load->model('M_sala');
                    $resBanco = $this->M_sala->consultar(
                        $this->getCodigo(),
                        $this->getDescricao(),
                        $this->getAndar(),
                        $this->getCapacidade()
                    );

                    if ($resBanco['codigo'] == 1) {
                        $sucesso = true;
                    } else {
                        // Captura erro do banco
                        $erros[] = [
                            'codigo' => $resBanco['codigo'],
                            'msg' => $resBanco['msg']
                        ];
                    }
                }
            }
        } catch (Exception $e) {
            $erros[] = ['codigo' => 0, 'msg' => 'Erro inesperado: ' . $e->getMessage()];
        }

        // Monta retorno único
        if ($sucesso == true) {
            $retorno = [
                'sucesso' => $sucesso,
                'codigo' => $resBanco['codigo'],
                'msg' => $resBanco['msg'],
                'dados' => $resBanco['dados']
            ];
        } else {
            $retorno = ['sucesso' => $sucesso, 'erros' => $erros];
        }

        // Transforma o array em JSON
        echo json_encode($retorno);
    }
}
