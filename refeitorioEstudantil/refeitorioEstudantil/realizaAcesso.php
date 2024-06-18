<!DOCTYPE html>
<html>
  <head>
    <title>Registra Acesso</title>
    <link rel="stylesheet" href="style.css">

  </head>
  <body>
    <?php
      require_once (__DIR__.'/conexoesBd.php');
      include('header.php');

      //session_start();    

      if (isset($_POST['concluir']) && !empty($_POST['idu'])) {
        $idu = limpaInputMalicioso($_POST['idu']);
        $idc = $_SESSION['username'][5];
        $idr = limpaInputMalicioso($_POST['idr']);

        $s = oci_parse($c, 
          "INSERT INTO LOG_ACESSO(ID_USR,ID_CAIXA,ID_REF) VALUES ('$idu','$idc','$idr')"
        );
        if (!$s) {
          $m = oci_error($c);
          trigger_error("Erro oci parse: ". $m["message"], E_USER_ERROR);
        }

        $r = oci_execute($s); // for PHP <= 5.3.1 use OCI_DEFAULT instead
        if (!$r) {
          $m = oci_error($r);
          trigger_error("Erro oci execute: ". $m["message"], E_USER_ERROR);
        }
        oci_commit($c);
      }
    ?>
    <main>
      <div id="accessPag">
        <div>
          <h2>Registrar Acesso de Usuario</h2>
          <button onclick="window.location.href = 'caixa.php'">Voltar ao Menu</button>
        </div>
          <form method="post">
            <div class="container">
              <label for="idu"><b>Identificacao:</b></label>
              <input type="text" placeholder="ID Usuario" name="idu" required>

              <label for="idr"><b>Refeição:</b></label>
              <input type="text" placeholder="ID Refeição" name="idr" required>

              <br>
              <button type="submit" name="concluir">Concluir</button>
              <input type="reset" value="Limpar">
            </div>
          </form>
        <div>
          <?php
            $s=oci_parse($c, "SELECT * from LOG_ACESSO order by DATA_E_HORA ASC");

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
              
               echo "<table id=\"tabelaAccess\" border=\"1\">\n";
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
            echo "$nrows Registros de Acessos<br />\n";
          ?>
      </div>
    </main>
    <?php include('footer.php'); ?>
  </body>
</html>