<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("M_sala.php");
include_once("M_horario.php");
include_once("M_turma.php");
include_once("M_professor.php");

class M_mapa extends CI_Model{

    public function inserir($dataReserva, $codSala, $codHorario, $codTurma, $codProfessor){
        try {
            $retornoConsultaReservaTotal = $this->consultaReservaTotal($dataReserva, $codSala, $codHorario);

            if ($retornoConsultaReservaTotal['codigo'] == 11 || $retornoConsultaReservaTotal['codigo'] == 7) {
                $salaObj = new M_sala();
                $retornoConsultaSala = $salaObj->consultar($codSala, '', '', '');

                if ($retornoConsultaSala['codigo'] == 1) {
                    $horarioObj = new M_horario();
                    $retornoConsultaHorario = $horarioObj->consultarHorario($codHorario, '', '');

                    if ($retornoConsultaHorario['codigo'] == 10) {
                        $turmaObj = new M_turma();
                        $retornoConsultaTurma = $turmaObj->consultaTurmaCod($codTurma);

                        if ($retornoConsultaTurma['codigo'] == 10) {
                            $professorObj = new M_professor();
                            $retornoConsultaProfessor = $professorObj->consultar($codProfessor, '', '', '');

                            if ($retornoConsultaProfessor['codigo'] == 1) {
                                $this->db->query("insert into tbl_mapa (datareserva, sala, codigo_horario, codigo_turma, codigo_professor)
                                values ('" . $dataReserva . "', " . $codSala . ", " . $codHorario . ", " . $codTurma . ", " . $codProfessor . ")");

                                if ($this->db->affected_rows() > 0) {
                                    $dados = array(
                                        'codigo' => 1,
                                        'msg' => 'Agendamento cadastrado corretamente.'
                                    );
                                } else {
                                    $dados = array(
                                        'codigo' => 9,
                                        'msg' => 'Houve algum problema na inserção na tabela de agendamento.'
                                    );
                                }
                            } else {
                                $dados = array(
                                    'codigo' => $retornoConsultaProfessor['codigo'],
                                    'msg' => $retornoConsultaProfessor['msg']
                                );
                            }
                        } else {
                            $dados = array(
                                'codigo' => $retornoConsultaTurma['codigo'],
                                'msg' => $retornoConsultaTurma['msg']
                            );
                        }
                    } else {
                        $dados = array(
                            'codigo' => $retornoConsultaHorario['codigo'],
                            'msg' => $retornoConsultaHorario['msg']
                        );
                    }
                } else {
                    $dados = array(
                        'codigo' => $retornoConsultaSala['codigo'],
                        'msg' => $retornoConsultaSala['msg']
                    );
                }
            } else {
                $dados = array(
                    'codigo' => $retornoConsultaReservaTotal['codigo'],
                    'msg' => $retornoConsultaReservaTotal['msg']
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

    private function consultaReservaTotal($dataReserva, $codSala, $codHorario){
        try {
            $sql = "select * from tbl_horario where codigo = $codHorario";
            $retornoHorario = $this->db->query($sql);

            if ($retornoHorario->num_rows() > 0) {
                $linhaHr = $retornoHorario->row();
                $horaInicial = $linhaHr->hora_ini;
                $horaFinal = $linhaHr->hora_fim;

                $sql = "select * from tbl_mapa m, tbl_horario h
                where m.datareserva = '" . $dataReserva . "'
                and m.sala = $codSala
                and m.codigo_horario = h.codigo
                and (h.hora_fim >= '" . $horaInicial . "'
                and h.hora_ini <= '" . $horaFinal . "')";
                $retornoMapa = $this->db->query($sql);

                if ($retornoMapa->num_rows() > 0) {
                    $linha = $retornoMapa->row();

                    if (trim($linha->estatus) == "D") {
                        $dados = array(
                            'codigo' => 7,
                            'msg' => 'Agendamento desativado no sistema.'
                        );
                    } else {
                        $dados = array(
                            'codigo' => 8,
                            'msg' => 'A data de ' . $dataReserva . ' está ocupada para esta sala'
                        );
                    }
                } else {
                    $dados = array(
                        'codigo' => 11,
                        'msg' => 'Reserva não encontrada.'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => 11,
                    'msg' => 'Reserva não encontrada.'
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

    public function consultar($codigo, $dataReserva, $codSala, $codHorario, $codTurma, $codProfessor){
        try {
            $sql = "select m.codigo, date_format(m.datareserva,'%d-%m-%Y') datareservabra, datareserva,
                m.sala, s.descricao descsala, m.codigo_horario,
                h.descricao deshorario, m.codigo_turma, t.descricao descturma, m.codigo_professor,
                p.nome nome_professor
                from tbl_mapa m, tbl_professor p, tbl_horario h, tbl_turma t, tbl_sala s
                where m.estatus = ''
                and m.codigo_professor = p.codigo
                and m.codigo_horario = h.codigo
                and m.codigo_turma = t.codigo
                and m.sala = s.codigo ";

            if (trim($codigo) != '') {
                $sql = $sql . "and m.codigo = $codigo ";
            }

            if (trim($dataReserva) != '') {
                $sql = $sql . "and m.datareserva = '" . $dataReserva . "' ";
            }

            if (trim($codSala) != '') {
                $sql = $sql . "and m.sala = $codSala ";
            }

            if (trim($codHorario) != '') {
                $sql = $sql . "and m.codigo_horario = $codHorario ";
            }

            if (trim($codTurma) != '') {
                $sql = $sql . "and m.codigo_turma = $codTurma ";
            }

            if (trim($codProfessor) != '') {
                $sql = $sql . "and m.codigo_professor = $codProfessor ";
            }

            $sql = $sql . " order by m.datareserva, h.hora_ini, m.codigo_horario, m.sala ";

            $retorno = $this->db->query($sql);

            if ($retorno->num_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Consulta efetuada com sucesso.',
                    'dados' => $retorno->result()
                );
            } else {
                $dados = array(
                    'codigo' => 11,
                    'msg' => 'Agendamento(s) não encontrado(s).'
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

    public function alterar($codigo, $dataReserva, $codSala, $codHorario, $codTurma, $codProfessor){
        try {
            $retornoConsultaCodigo = $this->consultar(
                $codigo,
                "",
                "",
                "",
                "",
                ""
            );

            if ($retornoConsultaCodigo['codigo'] == 1) {

                $query = "update tbl_mapa set ";

                if ($dataReserva != "") {
                    $query .= "datareserva = '$dataReserva', ";
                }

                if ($codSala != "") {
                    $salaObj = new M_sala();
                    $retornoConsultaSala = $salaObj->consultar($codSala, '', '', '');

                    if ($retornoConsultaSala['codigo'] == 1) {
                        $query .= "sala = $codSala, ";
                    } else {
                        return array(
                            'codigo' => $retornoConsultaSala['codigo'],
                            'msg' => $retornoConsultaSala['msg']
                        );
                    }
                }

                if ($codHorario != "") {
                    $horarioObj = new M_horario();
                    $retornoConsultaHorario = $horarioObj->consultarHorario($codHorario, '', '');

                    if ($retornoConsultaHorario['codigo'] == 1) {
                        $query .= "codigo_horario = $codHorario, ";
                    } else {
                        return array(
                            'codigo' => $retornoConsultaHorario['codigo'],
                            'msg' => $retornoConsultaHorario['msg']
                        );
                    }
                }

                if ($codTurma != "") {
                    $turmaObj = new M_turma();
                    $retornoConsultaTurma = $turmaObj->consultaTurmaCod($codTurma);

                    if ($retornoConsultaTurma['codigo'] == 1) {
                        $query .= "codigo_turma = $codTurma, ";
                    } else {
                        return array(
                            'codigo' => $retornoConsultaTurma['codigo'],
                            'msg' => $retornoConsultaTurma['msg']
                        );
                    }
                }

                if ($codProfessor != "") {
                    $professorObj = new M_professor();
                    $retornoConsultaProfessor = $professorObj->consultar($codProfessor, '', '', '');

                    if ($retornoConsultaProfessor['codigo'] == 1) {
                        $query .= "codigo_professor = $codProfessor, ";
                    } else {
                        return array(
                            'codigo' => $retornoConsultaProfessor['codigo'],
                            'msg' => $retornoConsultaProfessor['msg']
                        );
                    }
                }

                $queryFinal = rtrim($query, ", ") . " where codigo = $codigo";
                $this->db->query($queryFinal);

                if ($this->db->affected_rows() > 0) {
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Agendamento alterado corretamente.'
                    );
                } else {
                    $dados = array(
                        'codigo' => 9,
                        'msg' => 'Houve algum problema na alteração na tabela de agendamento.'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => $retornoConsultaCodigo['codigo'],
                    'msg' => $retornoConsultaCodigo['msg']
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

    public function desativar($codigo){
        try {
            $retornoConsulta = $this->consultar(
                $codigo,
                "",
                "",
                "",
                "",
                ""
            );

            if ($retornoConsulta['codigo'] == 1) {

                $this->db->query("delete from tbl_mapa where codigo = $codigo");

                if ($this->db->affected_rows() > 0) {
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Agendamento DESATIVADO corretamente.'
                    );
                } else {
                    $dados = array(
                        'codigo' => 9,
                        'msg' => 'Houve algum problema na DESATIVAÇÃO do Agendamento.'
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
?>
