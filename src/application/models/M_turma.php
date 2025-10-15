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

    public function alterar($codigo, $descricao, $capacidade, $dataInicio)
{
    try {
        $retornoConsulta = $this->consultaTurmaCod($codigo);

        if ($retornoConsulta['codigo'] == 10) {
            $query = "UPDATE tbl_turma SET ";
            $updates = [];

            if ($descricao !== '') {
                $updates[] = "descricao = '$descricao'";
            }
            if ($capacidade !== '') {
                $updates[] = "capacidade = '$capacidade'";
            }
            if ($dataInicio !== '') {
                $updates[] = "dataInicio = '$dataInicio'";
            }

            $query .= implode(", ", $updates) . " WHERE codigo = $codigo ";

            $params = [];
            if ($descricao !== '') {
                $params[] = $descricao;
            }
            if ($capacidade !== '') {
                $params[] = $capacidade;
            }
            if ($dataInicio !== '') {
                $params[] = $dataInicio;
            }
            $params[] = $codigo;

            $this->db->query($query, $params);

            if ($this->db->affected_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Turma atualizada corretamente.'
                );
            } else {
                $dados = array(
                    'codigo' => 8,
                    'msg' => 'Houve algum problema na atualização na tabela de turma.'
                );
            }
        } else {
            $dados = array(
                'codigo' => 5,
                'msg' => 'Turma não cadastrada no sistema.'
            );
        }
    } catch (Exception $e) {
        $dados = array(
            'codigo' => 00,
            'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
        );
    }

    return $dados;
}

private function consultaTurmaCod($codigo)
{
    try {
        $sql = "select * from tbl_turma where codigo = $codigo ";
        $retornoTurma = $this->db->query($sql);

        if ($retornoTurma->num_rows() > 0) {
            $linha = $retornoTurma->row();
            if (trim($linha->estatus) == "D") {
                $dados = array(
                    'codigo' => 9,
                    'msg' => 'Turma desativada no sistema.'
                );
            } else {
                $dados = array(
                    'codigo' => 10,
                    'msg' => 'Consulta efetuada com sucesso.'
                );
            }
        } else {
            $dados = array(
                'codigo' => 12,
                'msg' => 'Turma não encontrada.'
            );
        }
    } catch (Exception $e) {
        $dados = array(
            'codigo' => 00,
            'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
        );
    }

    return $dados;
}

public function desativar($codigo)
{
    try {
        $retornoConsulta = $this->consultaTurmaCod($codigo);

        if ($retornoConsulta['codigo'] == 10) {
            $this->db->query("update tbl_turma set estatus = 'D' where codigo = $codigo");

            if ($this->db->affected_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Turma DESATIVADA corretamente.'
                );
            } else {
                $dados = array(
                    'codigo' => 8,
                    'msg' => 'Houve algum problema na DESATIVAÇÃO da turma.'
                );
            }
        } else {
            $dados = array(
                'codigo' => $retornoConsulta['codigo'],
                'msg' => $retornoConsulta['msg']
            );
        }
    } catch (Exception $e) {
        $dados = array(
            'codigo' => 00,
            'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
        );
    }

    return $dados;
}


}


