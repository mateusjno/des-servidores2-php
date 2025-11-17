<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_horario extends CI_Model
{
    public function inserir($descricao, $horaInicial, $horaFinal)
    {
        try {
            $retornoConsulta = $this->consultarHorario('', $horaInicial, $horaFinal);

            if ($retornoConsulta['codigo'] != 0 && $retornoConsulta['codigo'] != 10) {
                $this->db->query("INSERT INTO tbl_horario (descricao, hora_ini, hora_fim)
                                  VALUES ('$descricao', '$horaInicial', '$horaFinal')");

                if ($this->db->affected_rows() > 0) {
                    $dados = [
                        'codigo' => 1,
                        'msg' => 'Horário cadastrado corretamente.'
                    ];
                } else {
                    $dados = [
                        'codigo' => 8,
                        'msg' => 'Houve algum problema na inserção na tabela de horários.'
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

    public function consultarHorario($codigo, $horaInicial, $horaFinal)
    {
        try {
            if ($codigo != '') {
                $sql = "SELECT * FROM tbl_horario WHERE codigo = $codigo";
            } else {
                $sql = "SELECT * FROM tbl_horario
                        WHERE hora_ini = '$horaInicial'
                        AND hora_fim = '$horaFinal'";
            }

            $retornoHorario = $this->db->query($sql);

            if ($retornoHorario->num_rows() > 0) {
                $linha = $retornoHorario->row();
                if (trim($linha->estatus) == "D") {
                    $dados = [
                        'codigo' => 9,
                        'msg' => 'Horário desativado no sistema, caso precise reativar o mesmo, fale com o administrador.'
                    ];
                } else {
                    $dados = [
                        'codigo' => 10,
                        'msg' => 'Horário já cadastrado no sistema.'
                    ];
                }
            } else {
                $dados = [
                    'codigo' => 98,
                    'msg' => 'Horário não encontrado.'
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

    public function consultar($codigo, $descricao, $horaInicial, $horaFinal)
    {
        try {
            $sql = "SELECT * FROM tbl_horario WHERE estatus = ''";

            if (trim($codigo) != '') {
                $sql .= " AND codigo = $codigo";
            }

            if (trim($descricao) != '') {
                $sql .= " AND descricao LIKE '%$descricao%'";
            }

            if (trim($horaInicial) != '') {
                $sql .= " AND hora_ini = '$horaInicial'";
            }

            if (trim($horaFinal) != '') {
                $sql .= " AND hora_fim = '$horaFinal'";
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
                    'msg' => 'Horário não encontrado.'
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

    public function alterar($codigo, $descricao, $horaInicial, $horaFinal)
    {
        try {
            $retornoConsulta = $this->consultar($codigo, '', '', '');

            if ($retornoConsulta['codigo'] == 1) {
                $query = "UPDATE tbl_horario SET ";

                if ($descricao !== '') {
                    $query .= "descricao = '$descricao', ";
                }

                if ($horaInicial !== '') {
                    $query .= "hora_ini = '$horaInicial', ";
                }

                if ($horaFinal !== '') {
                    $query .= "hora_fim = '$horaFinal', ";
                }

                $queryFinal = rtrim($query, ", ") . " WHERE codigo = $codigo";

                $this->db->query($queryFinal);

                if ($this->db->affected_rows() > 0) {
                    $dados = [
                        'codigo' => 1,
                        'msg' => 'Horário atualizado corretamente.'
                    ];
                } else {
                    $dados = [
                        'codigo' => 8,
                        'msg' => 'Houve algum problema na atualização na tabela de horário.'
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
            $retornoConsulta = $this->consultarHorario($codigo, '', '');

            if ($retornoConsulta['codigo'] == 10) {
                $this->db->query("UPDATE tbl_horario SET estatus = 'D' WHERE codigo = $codigo");

                if ($this->db->affected_rows() > 0) {
                    $dados = [
                        'codigo' => 1,
                        'msg' => 'Horário DESATIVADO corretamente.'
                    ];
                } else {
                    $dados = [
                        'codigo' => 8,
                        'msg' => 'Houve algum problema na DESATIVAÇÃO do Horário.'
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