<!DOCTYPE html>
<html>
  <head>
    <title>Login</title>
  </head>
  <body>
    <?php
      include('header.php');
      require_once (__DIR__.'/conexoesBd.php');

      if (isset($_POST['login']) && !empty($_POST['uname'])&&!empty($_POST['psw'])) {
        $nome = limpaInputMalicioso($_POST['uname']);
        $senha = limpaInputMalicioso($_POST['psw']);

        $s = oci_parse($c, 'SELECT * from CAIXA where NOME=:ul and SENHA=:us');
        if (!$s) {
          $m = oci_error($c);
          trigger_error("Erro oci: ". $m["message"], E_USER_ERROR);
        }

        oci_bind_by_name($s, ":ul", $nome);
        oci_bind_by_name($s, ":us", $senha);

        $r= oci_execute($s);

        if (!$r) {
          $m = oci_error($r);
          trigger_error("Erro oci: ". $m["message"], E_USER_ERROR);
        }

        $row = oci_fetch_array($s, OCI_ASSOC);

        if ($row){

          $_SESSION['valid'] = true;
          $_SESSION['timeout'] = time();
          $_SESSION['username'] = $nome;
 
          header("location: caixa.php");
        } else {
          echo '<script type = "text/javascript">
              alert("Credencial Invalida...");
            </script>
          ';
        }
      }
    ?>
    <main>
      <div>
        <div id="loginPag">
          <h2>Entrar como Caixa</h2>
        </div>
        <form method="post">
          <!-- <div class="imgcontainer">
            
          </div> -->

          <div class="container">
            <label for="uname"><b>Login:</b></label>
            <input type="text" placeholder="Caixa Nome" name="uname" required>

            <label for="psw"><b>Senha</b></label>
            <input type="password" placeholder="Caixa Senha" name="psw" required>

            <button type="submit" name="login">Entrar</button>
            <input type="reset" value="Limpar">
          </div>
        </form>
      </div>  
    </main>
    <?php include('footer.php'); ?>
  </body>
</html>