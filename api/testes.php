<?php
  require_once "../../../config/config.php";

  // Dados do Banco de dados propriamente dito
  $host = CONF_BD_SERVER;
  $usuario = CONF_BD_USER;
  $senha = CONF_BD_PASSWD;
  $banco = CONF_BD_DATABASE;

  // Funções auxiliares
  function testarBanco() {
    global $host, $usuario, $senha, $banco;

    $str  = "host=" . $host;
    $str .= " port=5432";
    $str .= " dbname=" . $banco;
    $str .= " user=" . $usuario;
    $str .= " password=" . $senha;
    $conexao = pg_connect($str);

    if($conexao) {
      echo 'Conexão com o banco de dados funcionando normalmente. <br>';
    } else {
      echo '<strong style=\"color: #cd0000;\"><i>ERRO:</i></strong> <strong>Não foi possível conectar ao banco de dados</strong> <br>';
    }

    $q1 = "SELECT 'Contratos'
     as TipoDocumento, count(id_obra)
     as qtd
     FROM contrato
     WHERE caminho_contrato
     IS NOT NULL";
     $q2 = "SELECT 'Imagens'
     as TipoDocumento, count(id_obra)
     as qtd
     FROM foto
     WHERE caminho
     IS NOT NULL";

     queryAndShowData($conexao, $q1, "Documentos");
     queryAndShowData($conexao, $q2, "Imagens");

    return pg_close($conexao);
  }

  function queryAndShowData($con, $q, $nome) {
    $query = pg_query(
      $con,
      $q
    );

    if($query) {
      $n = 0;

      while($cons = pg_fetch_assoc($query)) {
        $n =  $cons['qtd'];
      }

      echo "A pasta $nome deve conter <strong>$n</strong> arquivos <br>";
    } else { echo "<strong style=\"color: #cd0000;\"><i>ERRO:</i></strong> Falha ao realizar a query no banco de dados"; }
  }

  function testarPermissoes() {
    $tmp = is_writable("../../relatorio/tmp");
    $templates = is_writable("../../templates");
    $uploads = is_writable("../../uploads");

    permissions($tmp, "tmp");
    permissions($templates, "templates");
    permissions($uploads, "uploads");
  }

  function permissions($dir, $nome) {
    if($dir) {
      echo "Permissão para a pasta <strong>\"$nome\"</strong> concedida e funcionando normalmente. <br>";
    } else {
      echo "<strong style='color: #cd0000;''><i>ERRO:</i></strong> A pasta <strong>\"$nome\"</strong> não contém permissões de escrita <br>";
    }
  }

  function testarModulos() {
    gerarModulos(PHP_MODULES);
  }

  function testarQuantidadeArquivos() {
    files("../../uploads/imagens", "uploads/imagens");
    files("../../uploads/documentos", "upload/documentos");
  }

  function files($dir, $nome) {
    $c = 0;
    $n = new FilesystemIterator($dir, FilesystemIterator::SKIP_DOTS);
    foreach ($n as $key => $value) {
      $c++;
    }

    echo "A pasta <strong>\"$nome\"</strong> contém $c arquivos <br>";
  }

  function gerarModulos($modulos) {
    foreach ($modulos as $key) {
      if(extension_loaded($key)) {
        echo "O módulo <strong>\"$key\"</strong> está habilitado <br>";
      } else {
        echo "<strong style=\"color: #cd0000;\"><i>ERRO:</i></strong> O módulo <strong>\"$key\"</strong> não está habilitado <br>";
      }
    }
  }

  call_user_func($_POST['function']);
?>
