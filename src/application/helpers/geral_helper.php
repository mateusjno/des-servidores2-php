<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
   Função para verificar os parâmetros vindos do FrontEnd
   */
function verificarParam($atributos, $lista)
{
    //1º   -Verificar se os elementos do Front estão nos atributos necessários
    foreach ($lista as $key => $value) {
        if (array_key_exists($key, get_object_vars($atributos))) {
            $estatus = 1;
        } else {
            $estatus = 0;
            break;
        }
    }

    // 2º   -Verificando a quantidade de elementos
    if (count(get_object_vars($atributos)) != count($lista)) {
        $estatus = 0;
    }

    return $estatus;
}

function validarDados($valor, $tipo, $tamanhoZero = true)
{
    if (is_null($valor) || $valor === '') {
        return array('codigoHelper' => 2, 'msg' => 'Conteúdo nulo ou vazio');
    }
    if ($tamanhoZero && ($valor === 0 || $valor === 0)) {
        return array('codigoHelper' => 3, 'msg' => 'Conteúdo zerado.');
    }

    switch ($tipo) {
        case 'int':
            if (filter_var($valor, FILTER_VALIDATE_INT) === false) {
                return array('codigoHelper' => 4, 'msg' => 'Conteúdo não inteiro.');
            }
            break;
        case 'string':
            if (!is_string($valor) || trim($valor) === '') {
                return array('codigoHelper' => 5, 'msg' => 'Conteúdo não é um texto.');
            }
            break;

        case 'date':
            if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $valor, $match)) {
                return array('codigoHelper' => 6, 'msg' => 'Data em formato inválido.');
            } else {
                $d = DateTime::createFromFormat('Y-m-d', $valor);
                if (($d && $d->format('Y-m-d') === $valor) === false) {
                    return array('codigoHelper' => 6, 'msg' => 'Data inválida.');
                }
            }
            break;

        case 'hora':
            if (!preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9]$/', $valor)) {
                return array('codigoHelper' => 7, 'msg' => 'Hora em formato inválido.');
            }
            break;

        default:
            return array('codigoHelper' => 0, 'msg' => 'Tipo de dado não definido.');
    }
    return array('codigoHelper' => 0, 'msg' => 'Validação correta.');
}


/*
 * Função para verificar os tipos de dados para Consulta
 */
function validarDadosConsulta($valor, $tipo) {
    if ($valor != '') {
        switch ($tipo) {
            case 'int':
                // Filtra como inteiro, aceita '123' ou 123
                if (filter_var($valor, FILTER_VALIDATE_INT) === false) {
                    return array('codigoHelper' => 4, 'msg' => 'Conteúdo não inteiro.');
                }
                break;
            case 'string':
                // Garante que é string não vazia após trim
                if (!is_string($valor) || trim($valor) === '') {
                    return array('codigoHelper' => 5, 'msg' => 'Conteúdo não é um texto.');
                }
                break;
            case 'date':
                // Verifico se tem padrão de data
                if(!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $valor, $match)){
                    return array('codigoHelper' => 6, 'msg' => 'Data em formato inválido.');
                } else {
                    // Tenta criar DateTime no formato Y-m-d
                    $d = DateTime::createFromFormat('Y-m-d', $valor);
                    if (($d->format('Y-m-d') === $valor) == false) {
                        return array('codigoHelper' => 6, 'msg' => 'Data inválida.');
                    }
                }
                break;
            case 'hora':
                // Verifico se tem padrão de hora
                if(!preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $valor)){
                    return array('codigoHelper' => 7, 'msg' => 'Hora em formato inválido.');
                }
                break;

            default:
                return array('codigoHelper' => 97, 'msg' => 'Tipo de dado não definido.');
        }
    }
    // Valor default da variável $retorno caso não ocorra erro
    return array('codigoHelper' => 0, 'msg' => 'Validação correta.');
}


