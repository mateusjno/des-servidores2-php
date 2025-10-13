<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_turma extends CI_Model
{
    public function inserir($descricao, $capacidade, $dataInicio)
    {
        try {
            $this->db->query("INSERT INTO tbl_turma (descricao, capacidade, dataInicio) VALUES ('$descricao', $capacidade, '$dataInicio')");

            if ($this->db->affected_rows() > 0) {
                $dados = [
                    'codigo' => 1,
                    'msg' => 'Turma cadastrada corretamente.'
                ];
            } else {
                $dados = [
                    'codigo' => 8,
                    'msg' => 'Houve algum problema na inserção na tabela de turma.'
                ];
            }
        } catch (Exception $e) {
            $dados = [
                'codigo' => 0,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
            ];
        }

        return $dados;
    }

    public function consultar($codigo, $descricao, $capacidade, $dataInicio)
    {
        try {
            $sql = "SELECT codigo, descricao, capacidade, dataInicio, 
                    DATE_FORMAT(dataInicio, '%d-%m-%Y') dataIniciobra 
                    FROM tbl_turma WHERE estatus = ''";

            if (trim($codigo) != '') {
                $sql .= " AND codigo = $codigo";
            }

            if (trim($descricao) != '') {
                $sql .= " AND descricao LIKE '%$descricao%'";
            }

            if (trim($capacidade) != '') {
                $sql .= " AND capacidade = $capacidade";
            }

            if (trim($dataInicio) != '') {
                $sql .= " AND dataInicio = '$dataInicio'";
            }

            $retorno = $this->db->query($sql);

            if ($retorno->num_rows() > 0) {
                $dados = [
                    'codigo' => 1,
                    'msg' => 'Consulta efetuada com sucesso',
                    'dados' => $retorno->result()
                ];
            } else {
                $dados = [
                    'codigo' => 11,
                    'msg' => 'Turma não encontrada.'
                ];
            }
        } catch (Exception $e) {
            $dados = [
                'codigo' => 0,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
            ];
        }

        return $dados;
    }
}
