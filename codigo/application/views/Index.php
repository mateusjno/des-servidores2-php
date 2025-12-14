<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= base_url('assets/css/reset.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js" defer></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" defer></script>

    <title>Home - Sistema Mapa de Sala</title>

    <link rel="icon" href="<?= base_url('assets/img/icone_fatecSR.ico') ?>" type="image/x-icon">
</head>

<body>
    <a id="linkHome" href="#">
        <header>
            <h1 id="headerTitle">Mapeamento de Salas</h1>
        </header>
    </a>
    <main>        
        <!-- Seção com cards -->
        <section class="secao4" id="sobre">
            <div class="secao4-div">

                <!-- Card 1 -->
                <a href="<?= base_url('funcoes/abreSala') ?>" class="secao4-div-card card">
                    <img decoding="async" src="<?= base_url('assets/img/sala-de-aula.png') ?>" alt="imagem do card 1 sala">
                    <h3>Sala de Aula</h3>
                    <p>Clique para Cadastrar, Editar, Consultar ou Excluir uma sala de aula.</p>
                </a>

                <!-- Card 2 -->
                <a href="<?= base_url('funcoes/abreProfessor') ?>" class="secao4-div-card card">
                    <img decoding="async" src="<?= base_url('assets/img/professores.png') ?>" alt="imagem do card 2 professores">
                    <h3>Docente</h3>
                    <p>Clique para Cadastrar, Editar, Consultar ou Excluir um(a) novo(a) docente.</p>
                </a>

                <!-- Card 3 -->
                <a href="<?= base_url('funcoes/abreTurma') ?>" class="secao4-div-card card">
                    <img decoding="async" src="<?= base_url('assets/img/turma.png') ?>" alt="imagem do card 3 turma">
                    <h3>Turma</h3>
                    <p>Clique para Cadastrar, Editar, Consultar ou Excluir uma nova turma.</p>
                </a>

                <!-- Card 4 -->
                <a href="<?= base_url('funcoes/abrePeriodo') ?>" class="secao4-div-card card">
                    <img decoding="async" src="<?= base_url('assets/img/periodo.png') ?>" alt="imagem do card 4 período">
                    <h3>Período</h3>
                    <p>Clique para Cadastrar, Editar, Consultar ou Excluir um novo período.</p>
                </a>

                <!-- Card 5 -->
                <a href="<?= base_url('funcoes/abreMapa') ?>" class="secao4-div-card card">
                    <img decoding="async" src="<?= base_url('assets/img/mapeamento.png') ?>" alt="imagem do card 5 mapeamento">
                    <h3>Reservas</h3>
                    <p>Clique para Cadastrar, Editar, Consultar ou Excluir uma Reserva de Sala.</p>
                </a>

                <!-- Card 6 -->
                <a href="<?= base_url('funcoes/abreRelatorio') ?>" class="secao4-div-card card">
                    <img decoding="async" src="<?= base_url('assets/img/relatorio.png') ?>" alt="imagem do card 6 relatório">
                    <h3>Relatório</h3>
                    <p>Clique para gerar Relatório de Mapeamento de Sala.</p>
                </a>
                
                <!-- Card 7 -->
                <a href="<?= base_url('Funcoes/encerraSistema') ?>" class="secao4-div-card card">
                    <img decoding="async" src="<?= base_url('assets/img/sair.png') ?>" alt="imagem do card 7 sair">
                    <h3>Sair do Sistema</h3>
                    <p>Encerrar.</p>
                </a>

            </div>
        </section>
    </main>
</body>
</html>
