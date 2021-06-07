<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "financesdb";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
        die("Conexão falhou: " . mysqli_connect_error());
    }

    // sql to delete a record
    if ($_GET) {
        $del = $_GET['id'];

        $sql = "DELETE FROM movimentos WHERE CodMovimento = $del";

        if (mysqli_query($conn, $sql)) {
            $msg = "Registro excluído com sucesso";
        } else {
            $msg_erro = "Erro ao excluir registro: " . mysqli_error($conn);
        }
    }


?>

<!doctype html>
<html lang="pt-br">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <title>Finances - Início</title>

    <link rel="icon" href="img/carteira.png" type="image/gif" sizes="32x32">

  </head>
  <body>

<!-- Cabeçalho -->
    <div class="container">
        <div style="border-radius: 5px; padding: 10px" class="row bg-light">
            <ul class="nav nav-pills">
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="index.php">Início</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="cadastrarReceitas.php">Cadastrar Receitas</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="cadastrarDespesas.php">Cadastrar Despesas</a>
                </li>
            </ul>
        </div>


<!-- Cards -->
      <div style="margin-top:10px; border-radius: 5px; padding: 10px" class="row bg-light">
        <!-- <h4 class="card-title">CARTEIRA</h4> -->
            <div class="col-4">
                <div class="card text-dark" style="max-width: 18rem;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <h4 class="card-title">RECEITA</h4>
                            </div>
                            <div class="col-3">
                                <img src="img/income.svg" alt="Logo" />
                            </div>
                        </div>
                        <hr>
                        <?php
                            $sql = "SELECT SUM(Valor) FROM movimentos WHERE CatReceitas_CodReceita is NOT null";
                            $result = mysqli_query($conn, $sql);
                            $receitas = mysqli_fetch_array($result);
                            $receita = $receitas[0];
                        ?>
                        <div style="font-size: 30px; margin: 0;">
                            R$<?=number_format($receita, 2, ',', '.');?>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-4">
                <div class="card text-dark" style="max-width: 18rem;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <h4 class="card-title">DESPESA</h4>
                            </div>
                            <div class="col-3">
                                <img src="img/expense.svg" alt="Logo" />
                            </div>
                        </div>
                        <hr>
                        <?php
                            $sql = "SELECT SUM(Valor) FROM movimentos WHERE CatDespesas_CodDespesa is NOT null";
                            $result = mysqli_query($conn, $sql);
                            $despesas = mysqli_fetch_array($result);
                            $despesa = $despesas[0];
                        ?>
                        <div style="font-size: 30px; margin: 0;">
                            R$<?=number_format($despesa, 2, ',', '.');?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-4">
                        <?php
                        if ($receita > $despesa) {
                        $saldo = $receita - $despesa;
                        ?>
                        <div class="card text-white bg-success" style="max-width: 18rem;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-9">
                                        <h4 class="card-title">SALDO</h4>
                                    </div>
                                    <div class="col-3">
                                        <img src="img/total.svg" alt="Logo" />
                                    </div>
                                </div>
                                <hr>
                                <div style="font-size: 30px; margin: 0;">
                                R$<?=number_format($saldo, 2, ',', '.');?>
                                </div>
                        <?php
                        }else { 
                            $saldo = $receita - $despesa; 
                        ?>
                        <div class="card text-white bg-danger" style="max-width: 18rem;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-9">
                                    <h4 class="card-title">SALDO</h4>
                                </div>
                                <div class="col-3">
                                    <img src="img/total.svg" alt="Logo" />
                                </div>
                            </div>
                            <hr>
                            <div style="font-size: 30px; margin: 0;">
                                R$<?=number_format($saldo, 2, ',', '.');?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>
      </div>
    </div>


<!-- Movimentos -->
    <div style="border-radius: 5px; padding: 10px; margin-top: 10px;" class="container bg-light">
        <h4>MOVIMENTOS </h4>
        <?php if (isset($msg)) {?>
          <div class="text text-success">
            <?=$msg?>
          </div>
          <?php } elseif(isset($msg_erro)) {?>
            <div class="text text-danger">
            <?=$msg_erro?>
           </div>
          <?php }?>
    <div style="max-height:300px; overflow-y:auto;" class="table-overflow">

    <?php

    $sql = "SELECT movimentos.CodMovimento, movimentos.DescMovimento, movimentos.DtMovimento, movimentos.Valor,
    catreceitas.DescReceita,
    catdespesas.DescDespesas
    FROM ((movimentos
    Left Join catreceitas ON movimentos.CatReceitas_CodReceita = catreceitas.CodReceita)
    Left Join catdespesas ON catdespesas.CodDespesas = movimentos.CatDespesas_CodDespesa)
    order by DtMovimento DESC";

    $results = $conn->query($sql);

    if ($results->num_rows > 0) {
    echo "<table class='table table-striped table-hover'>
    <tr>
    <th>DESCRIÇÃO</th>
    <th>DATA</th>
    <th>VALOR</th>
    <th>RECEITAS</th>
    <th>DESPESAS</th>
    <th style='text-align:center;'>AÇÃO</th>
    </tr>";
    // output data of each row
    while($row = $results->fetch_assoc()) {
        echo "
            <tr>
                <td>".$row["DescMovimento"]."</td>
                <td>".$row["DtMovimento"]."</td>
                <td>R$".number_format($row["Valor"], 2, ',', '.') ."</td>
                <td>".$row["DescReceita"]."</td>
                <td>".$row["DescDespesas"]."</td>
                <td style='text-align:center;'>
                <a href='?id=".$row["CodMovimento"]."' style='text-decoration: none; color:white' class='badge bg-danger'>EXCLUIR</a></td>
            </tr>";
    }
    echo "</table>";
    } else {
    echo "<span style='margin-left:43%; font-size: 13px;' class='badge rounded-pill bg-info text-dark'>Não há movimentos</span>";
    }
    $conn->close();
    ?>



    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
  </body>
</html>
