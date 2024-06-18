<!DOCTYPE html>
<html>
  <script type="text/javascript">
    function mostrarAvancado() {
      var checkBox = document.getElementById("avancado");
      var avancadoP = document.getElementById("avancadoP");

      if (checkBox.checked == true){
        avancadoP.style.display = "block";
      } else {
        avancadoP.style.display = "none";
      }
    }
  </script>
  <head>
    <title>Caixa</title>
  </head>
  <body>
    <?php 
      include('header.php');
      require_once (__DIR__.'../conexoesBd.php');
    ?>
    <main>
      <div>
        <div id="inicio">
          <h2>Operações</h2>
          <h3>Bem vindo, <?php echo $_SESSION['username']?>!</h3>
          
        </div>
        <nav>
          <ul>
            <li><a href="realizaTransferencia.php">Realizar Transferencia</a></li>
            <li><a href="realizaRecarga.php">Realizar Recarga</a></li>
            <li><a href="realizaAcesso.php">Realizar Acesso</a></li>
            <li style="float:right"><a href="index.php">Sair</a></li>
          </ul>
        </nav>
        <?php 
          $s=oci_parse($c, "SELECT * from USUARIO order by ID_USR ASC");

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
            
             echo "<table id=\"tabelaUsr\" border=\"1\">\n";
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
             echo "O banco de dados não pode obter os usuarios./>\n";
          }      
          echo "$nrows Usuarios Encontrados<br />\n";
        ?>
      </div>
      <div class="container">
        <script>
          function filtraTabela() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("inputFiltro");
            filter = input.value.toUpperCase();
            table = document.getElementById("tabelaUsr");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
              td = tr[i].getElementsByTagName("td")[4];
              if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                  tr[i].style.display = "";
                } else {
                  tr[i].style.display = "none";
                }
              }
            }
          }
        </script>
        <label for="inputFiltro"><b>Filtrar por:</b></label>
        <input type="text" id="inputFiltro" onkeyup="filtraTabela()" placeholder="Usuario CPF">


          <?php
            // if (isset($_POST['subcmd']) && !empty($_POST['cmd'])){
            //   $comando = "select * from RESTAURANTE;";//$_POST['cmd'];

            //   $query = oci_parse($c, $comando);
            //   if (!$query) {
            //     $m = oci_error($c);
            //     trigger_error("Erro no parse: ". $m["message"], E_USER_ERROR);
            //   }

            //   $r = oci_execute($query, OCI_NO_AUTO_COMMIT); // for PHP <= 5.3.1 use OCI_DEFAULT instead
            //   if (!$r) {
            //     $m = oci_error($s);
            //     $_SESSION['message'] = "Erro na Exec!\nMensagem de Erro: ".$m['message'];
            //     $_SESSION['msg_type'] = "danger";
            //     exit();
            //   }
            //   oci_commit($c);

            //   $_SESSION['message'] = "Comando Processado!";
            //   $_SESSION['msg_type'] = "warning";
            // }
          ?>
          <form method="post">
             <div class="container">
            </div>
          </form> 
        </div>
      </div>
    </main>
    <?php include('footer.php'); ?>
  </body>
</html>
