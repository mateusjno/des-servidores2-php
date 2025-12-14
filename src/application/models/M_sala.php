<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_sala extends CI_Model
{
    public function inserir($codigo, $descricao, $andar, $capacidade)
    {
        try {
            $retornoConsulta = $this->consultaSala($codigo);

            if ($retornoConsulta['codigo'] != 9 && $retornoConsulta['codigo'] != 10) {
                $this->db->query("INSERT INTO tbl_sala (codigo, descricao, andar, capacidade) VALUES ('$codigo', '$descricao', '$andar', '$capacidade')");

                if ($this->db->affected_rows() > 0) {
                    $dados = [
                        'codigo' => 1,
                        'msg' => 'Sala cadastrada corretamente'
                    ];
                } else {
                    $dados = [
                        'codigo' => 8,
                        'msg' => 'Houve algum problema na inserção na tabela de salas.'
                    ];
                }
            } else {
                $dados = [
                    'codigo' => $retornoConsulta['codigo'],
                    'msg' => $retornoConsulta['msg']
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

    private function consultaSala($codigo)
    {
        try {
            $sql = "SELECT * FROM tbl_sala WHERE codigo = '$codigo'";
            $retornoSala = $this->db->query($sql);

            if ($retornoSala->num_rows() > 0) {
                $linha = $retornoSala->row();
                if (trim($linha->estatus) == "D") {
                    $dados = [
                        'codigo' => 9,
                        'msg' => 'Sala desativada no sistema, caso precise reativar a mesma, fale com o administrador.'
                    ];
                } else {
                    $dados = [
                        'codigo' => 10,
                        'msg' => 'Sala já cadastrada no sistema.'
                    ];
                }
            } else {
                $dados = [
                    'codigo' => 98,
                    'msg' => 'Sala não encontrada.'
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

    public function consultar($codigo, $descricao, $andar, $capacidade)
    {
        try {
            $sql = "SELECT * FROM tbl_sala WHERE estatus = ''";

            if (trim($codigo) != '') {
                $sql .= " AND codigo = $codigo";
            }

            if (trim($andar) != '') {
                $sql .= " AND andar = '$andar'";
            }

            if (trim($descricao) != '') {
                $sql .= " AND descricao LIKE '%$descricao%'";
            }

            if (trim($capacidade) != '') {
                $sql .= " AND andar = '$capacidade'";
            }

            $sql .= " ORDER BY codigo";

            $retorno = $this->db->query($sql);

            if ($retorno->num_rows() > 0) {
                $dados = [
                    'codigo' => 1,
                    'msg' => 'Consulta efetuada com sucesso.',
                    'dados' => $retorno->result()
                ];
            } else {
                $dados = [
                    'codigo' => 11,
                    'msg' => 'Sala não encontrada.'
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

    public function alterar($codigo, $descricao, $andar, $capacidade)
    {
        try {
            $retornoConsulta = $this->consultaSala($codigo);

            if ($retornoConsulta['codigo'] == 10) {
                $query = "UPDATE tbl_sala SET ";

                if ($descricao !== '') {
                    $query .= "descricao = '$descricao', ";
                }

                if ($andar !== '') {
                    $query .= "andar = $andar, ";
                }

                if ($capacidade !== '') {
                    $query .= "capacidade = $capacidade, ";
                }

                $queryFinal = rtrim($query, ", ") . " WHERE codigo = $codigo";
                $this->db->query($queryFinal);

                if ($this->db->affected_rows() > 0) {
                    $dados = [
                        'codigo' => 1,
                        'msg' => 'Sala atualizada corretamente.'
                    ];
                } else {
                    $dados = [
                        'codigo' => 8,
                        'msg' => 'Houve algum problema na atualização na tabela de sala.'
                    ];
                }
            } else {
                $dados = [
                    'codigo' => $retornoConsulta['codigo'],
                    'msg' => $retornoConsulta['msg']
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

    public function desativar($codigo)
    {
        try {
            $retornoConsulta = $this->consultaSala($codigo);

            if ($retornoConsulta['codigo'] == 10) {
                $this->db->query("UPDATE tbl_sala SET estatus = 'D' WHERE codigo = $codigo");

                if ($this->db->affected_rows() > 0) {
                    $dados = [
                        'codigo' => 1,
                        'msg' => 'Sala DESATIVADA corretamente.'
                    ];
                } else {
                    $dados = [
                        'codigo' => 8,
                        'msg' => 'Houve algum problema na DESATIVAÇÃO da Sala.'
                    ];
                }
            } else {
                $dados = [
                    'codigo' => $retornoConsulta['codigo'],
                    'msg' => $retornoConsulta['msg']
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