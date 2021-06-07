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

  if ($_POST) {
      // print_r($_POST);

      $DescMovimento = $_POST['DescMovimento'];
      $DtMovimento = $_POST['DtMovimento'];
      $Valor = $_POST['Valor'];
      $CatDespesas_CodDespesa = $_POST['CatDespesas_CodDespesa'];

      $sql = "INSERT INTO movimentos (DescMovimento, DtMovimento, Valor, CatDespesas_CodDespesa)
      VALUES ('$DescMovimento', '$DtMovimento', $Valor, $CatDespesas_CodDespesa)";

      if (mysqli_query($conn, $sql)) {
          $msg = "Novo registro criado com sucesso";
          header("Location: index.php");

      } else {
          $msg_erro = "Erro: " . $sql . "<br>" . mysqli_error($conn);
      }
  }

  $sql = "SELECT * FROM catdespesas";
  $result = mysqli_query($conn, $sql);

?>

<!doctype html>
<html lang="pt-br">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <title>Finances - Cadastro Despesa</title>

    <link rel="icon" href="img/carteira.png" type="image/gif" sizes="32x32">

  </head>
  <body>

<!-- Cabeçalho -->
    <div class="container">
      <div style="border-radius: 5px; padding: 10px" class="row bg-light">
            <ul class="nav nav-pills">
                <li class="nav-item">
                <a class="nav-link" aria-current="page" href="index.php">Início</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="cadastrarReceitas.php">Cadastrar Receitas</a>
                </li>
                <li class="nav-item">
                <a class="nav-link active" href="cadastrarDespesas.php">Cadastrar Despesas</a>
                </li>
            </ul>
        </div>

  <!-- Form -->
  <br><div style="border-radius: 5px; padding: 10px" class="row bg-light">
        <div class="col">
          <form method="POST">
            <div class="form-group">
              <label>Descrição</label>
              <input type="text" name="DescMovimento" class="form-control" placeholder="Descrição:">
            </div>
            <div class="form-group">
              <label>Data</label>
              <input type="date" name="DtMovimento" class="form-control" placeholder="Data:">
            </div>
            <div class="form-group">
              <label>Valor</label>
              <input type="text" name="Valor" class="form-control" placeholder="Valor:">
            </div>
            <div class="form-group">
              <label>Categoria da Despesa</label>
              <select class="form-select" name="CatDespesas_CodDespesa">

                <?php
                if (mysqli_num_rows($result) > 0) {
                    // output data of each row
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row["CodDespesas"] . "'>" . $row["DescDespesas"] . '</option>';
                    }
                } else {
                    echo "<option>Sem despesas cadastradas</option>";
                }

                mysqli_close($conn);

                ?>

              </select>
            </div>

            <div class="form-group">
              <br><button type="submit" class="btn btn-primary">Enviar</button>
            </div>

          </form>

        </div>
      </div>

<!-- MSG ERROR -->
      <br><div class="row">
        <div class="col">
        <?php if (isset($msg)) {?>
          <div class="alert alert-success" role="alert">
            <?=$msg?>
          </div>
          <?php } elseif(isset($msg_erro)) {?>
            <div class="alert alert-danger" role="alert">
            <?=$msg_erro?>
           </div>
          <?php }?>
        </div>
      </div>
    </div>


    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
  </body>
</html>