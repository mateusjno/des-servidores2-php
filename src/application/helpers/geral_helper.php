<?php
defined('BASEPATH') or exit('No direct script access allowed');

function verificarParam($atributos, $lista)
{
    foreach ($lista as $key => $value) {
        if (array_key_exists($key, get_object_vars($atributos))) {
            $estatus = 1;
        } else {
            $estatus = 0;
            break;
        }
    }

    if (count(get_object_vars($atributos)) != count($lista)) {
        $estatus = 0;
    }

    return $estatus;
}

function validarDados($valor, $tipo, $tamanhoZero = true)
{
    if (is_null($valor) || $valor === '') {
        return ['codigoHelper' => 2, 'msg' => 'Conteúdo nulo ou vazio'];
    }
    if ($tamanhoZero && ($valor === 0 || $valor === 0)) {
        return ['codigoHelper' => 3, 'msg' => 'Conteúdo zerado.'];
    }

    switch ($tipo) {
        case 'int':
            if (filter_var($valor, FILTER_VALIDATE_INT) === false) {
                return ['codigoHelper' => 4, 'msg' => 'Conteúdo não inteiro.'];
            }
            break;
        case 'string':
            if (!is_string($valor) || trim($valor) === '') {
                return ['codigoHelper' => 5, 'msg' => 'Conteúdo não é um texto.'];
            }
            break;
        case 'date':
            if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $valor, $match)) {
                return ['codigoHelper' => 6, 'msg' => 'Data em formato inválido.'];
            } else {
                $d = DateTime::createFromFormat('Y-m-d', $valor);
                if (($d && $d->format('Y-m-d') === $valor) === false) {
                    return ['codigoHelper' => 6, 'msg' => 'Data inválida.'];
                }
            }
            break;
        case 'hora':
            if (!preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9]$/', $valor)) {
                return ['codigoHelper' => 7, 'msg' => 'Hora em formato inválido.'];
            }
            break;
        case 'email':
            if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
                return array(
                    'codigoHelper' => 8,
                    'msg' => 'E-mail em formato inválido.'
                );
            }
            break;
        default:
            return ['codigoHelper' => 0, 'msg' => 'Tipo de dado não definido.'];
    }
    return ['codigoHelper' => 0, 'msg' => 'Validação correta.'];
}

function validarDadosConsulta($valor, $tipo)
{
    if ($valor != '') {
        switch ($tipo) {
            case 'int':
                if (filter_var($valor, FILTER_VALIDATE_INT) === false) {
                    return ['codigoHelper' => 4, 'msg' => 'Conteúdo não inteiro.'];
                }
                break;
            case 'string':
                if (!is_string($valor) || trim($valor) === '') {
                    return ['codigoHelper' => 5, 'msg' => 'Conteúdo não é um texto.'];
                }
                break;
            case 'date':
                if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $valor, $match)) {
                    return ['codigoHelper' => 6, 'msg' => 'Data em formato inválido.'];
                } else {
                    $d = DateTime::createFromFormat('Y-m-d', $valor);
                    if (($d->format('Y-m-d') === $valor) == false) {
                        return ['codigoHelper' => 6, 'msg' => 'Data inválida.'];
                    }
                }
                break;
            case 'hora':
                if (!preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $valor)) {
                    return ['codigoHelper' => 7, 'msg' => 'Hora em formato inválido.'];
                }
                break;
            case 'email':
                if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
                    return array(
                        'codigoHelper' => 8,
                        'msg' => 'E-mail em formato inválido.'
                    );
                }
            break;
            default:
                return ['codigoHelper' => 97, 'msg' => 'Tipo de dado não definido.'];
        }
    }
    return ['codigoHelper' => 0, 'msg' => 'Validação correta.'];
}

function compararDataHora($valorInicial, $valorFinal, $tipo)
{
    $valorInicial = strtotime($valorInicial);
    $valorFinal   = strtotime($valorFinal);

    if ($valorInicial != '' && $valorFinal != '') {
        if ($valorInicial > $valorFinal) {
            switch ($tipo) {
                case 'hora':
                    return [
                        'codigoHelper' => 13,
                        'msg' => 'Hora Final menor que a Hora Inicial.'
                    ];
                case 'data':
                    return [
                        'codigoHelper' => 14,
                        'msg' => 'Data Final menor que a Data Inicial.'
                    ];
                default:
                    return [
                        'codigoHelper' => 97,
                        'msg' => 'Tipo de verificação não definida.'
                    ];
            }
        }
    }

    return [
        'codigoHelper' => 0,
        'msg' => 'Validação correta.'
    ];
}

function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    if (strlen($cpf) != 11) {
        return array('codigoHelper' => 15, 'msg' => 'CPF com menos de 11 dígitos.');
    }

    if (preg_match('/^(\d)\1{10}$/', $cpf)) {
        return array('codigoHelper' => 16, 'msg' => 'CPF com todos dígitos iguais.');
    }

    for ($t = 9; $t < 11; $t++) {
        $soma = 0;
        for ($i = 0; $i < $t; $i++) {
            $soma += $cpf[$i] * (($t + 1) - $i);
        }
        $digito = (10 * $soma) % 11;
        $digito = ($digito == 10) ? 0 : $digito;

        if ($cpf[$t] != $digito) {
            return array('codigoHelper' => 17, 'msg' => 'CPF com dígitos verificadores incorretos.');
        }
    }

    return array('codigoHelper' => 0, 'msg' => 'CPF válido.');
}


