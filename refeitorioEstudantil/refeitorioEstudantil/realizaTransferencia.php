<!DOCTYPE html>
<html>
  <head>
    <title>Registra Transferencia</title>
    <link rel="stylesheet" href="style.css">

  </head>
  <body>
    <?php
      require_once (__DIR__.'/conexoesBd.php');
      include('header.php');

      //session_start();    

      if (isset($_POST['concluir']) && !empty($_POST['idud']) && !empty($_POST['iduc'] && !empty($_POST['valor']))) {
        $idud = limpaInputMalicioso($_POST['idud']);
        $iduc = limpaInputMalicioso($_POST['iduc']);
        $idc = $_SESSION['username'][5];
        $val = limpaInputMalicioso($_POST['valor']);

        $s = oci_parse($c, 
          "INSERT INTO LOG_TRANSFERENCIA(VALOR, ID_USR_DEB, ID_USR_CRD, ID_CAIXA) values (
            '$val',
            '$idud', '$iduc', 
            '$idc'
          )"
        );

        if (!$s) {
          $m = oci_error($c);
          trigger_error("Erro oci: ". $m["message"], E_USER_ERROR);
        }
        $r = oci_execute($s); // for PHP <= 5.3.1 use OCI_DEFAULT instead
        if (!$r) {
          $m = oci_error($r);
          trigger_error("Erro oci: ". $m["message"], E_USER_ERROR);
        }
        oci_commit($c);
      }
    ?>
    <main>
      <div id="transfPag">
        <div>
          <h2>Registrar Tranferencia entre Usuarios</h2>
          <button onclick="window.location.href = 'caixa.php'">Voltar ao Menu</button>
        </div>
        <div>
          <form method="post">
            <div class="container">
              <label for="idud"><b>Identificacao Debitado:</b></label>
              <input type="text" placeholder="ID Usuario" name="idud" required>

              <label for="iduc"><b>Identificacao Creditado:</b></label>
              <input type="text" placeholder="ID Usuario" name="iduc" required>

              <label for="valor"><b>Valor:</b></label>
              <input type="text" placeholder='R$' name="valor" required>

              <br>
              <button type="submit" name="concluir">Concluir</button>
              <input type="reset" value="Limpar">
            </div>
          </form>
        </div> 
        <div>
            <?php
              $s=oci_parse($c, "SELECT * from LOG_TRANSFERENCIA order by DATA_E_HORA ASC");

              if (!$s) {
                  $m = oci_error($c);
                  trigger_error("Erro oci: ". $m["message"], E_USER_ERROR);
              }

              $r = oci_execute($s); // for PHP <= 5.3.1 use OCI_DEFAULT instead

              if (!$r) {
                  $m = oci_error($r);
                  trigger_error("Erro oci: ". $m["message"], E_USER_ERROR);
              }

              $nrows = oci_fetch_all($s, $results);
              if ($nrows > 0) {
                
                 echo "<table id=\"tabelaTransf\" border=\"1\">\n";
                 echo "<tr>\n";
                 foreach ($results as $key => $val) {
                    echo "<th>$key</th>\n";
                 }
                 echo "</tr>\n";
                 
                 for ($i = 0; $i < $nrows; $i++) {
                    echo "<tr>\n";
                    foreach ($results as $data) {
                       echo "<td>$data[$i]</td>\n";
                    }
                    echo "</tr>\n";
                 }
                 echo "</table>\n";
              } else {
                 echo "O banco de dados não pode obter os registros de acesso./>\n";
              }      
              echo "$nrows Registros de Tranferencia<br />\n";
            ?>
        </div>
      </main>
      <?php include('footer.php'); ?>
  </body>
</html>