const isNotLoading = `far fa-question-circle`;
const isNotOk = `far fa-times-circle`;
const isOk = `far fa-check-circle`;
const isLoading = `far spinner`;
const URL = "api/testes.php";

const closeModal = document.querySelector("#close-modal");
const popUpBtnYes = document.querySelector("#sim");
const popUpBtnNo = document.querySelector("#nao");
const popUp = document.querySelector(".pop-up");

const testBtn = document.querySelector("#testarVariaveisBtn");
const clearBtn = document.querySelector("#limparBtn");
const result = document.querySelector("#resultado");

const loaders = {
  permissao: document.querySelector("#permissaoLoader"),
  arquivos: document.querySelector("#arquivosLoader"),
  modulos: document.querySelector("#modulosLoader"),
  conexao: document.querySelector("#conexaoLoader"),
}


function ativarModal() {
  popUp.style.opacity = 1;
  popUp.style.transform = "translate(-50%, 0px)";
}

function fecharModal() {
  popUp.style.opacity = 0;
  popUp.style.transform = "translate(-50%, -2000px)";
}

const modulos = [
  { func: "testarPermissoes", loader: "permissao" },
  { func: "testarBanco", loader: "conexao" },
  { func: "testarModulos", loader: "modulos" },
  { func: "testarQuantidadeArquivos", loader: "arquivos" }
]

function testarDados() {
  for(var i in loaders) { loaders[i].className = isLoading }
  modulos.forEach(modulo => {
    testarModulo(URL, modulo.func, modulo.loader);
  });
  result.innerHTML +=
  `
  <hr>
    <span class="data">Horário de verificação: ${new Date().getHours()}:${new Date().getMinutes()}:${new Date().getSeconds()}</span>
  <hr>
  `;
  setTimeout(function() {
    scroll(document.querySelector("#resultado"));
  }, 200);
}

function testarModulo(url, obj, nome) {
  $.post(url, { function: obj }, function(res) {
    result.innerHTML += `<span>${res}</span>`;
    res.toLowerCase().includes("erro") || res === "" ? loaders[nome].className = isNotOk : loaders[nome].className = isOk;
  });
}


function scroll(elem) {
  elem.scrollTop = elem.scrollHeight + elem.clientHeight;
}

testBtn.addEventListener("click", testarDados);
clearBtn.addEventListener("click", function() {
  ativarModal();
});
popUpBtnNo.addEventListener("click", fecharModal);
popUpBtnYes.addEventListener("click", function() {
  result.innerHTML = "";
  fecharModal();
});
closeModal.addEventListener("click", fecharModal);
window.addEventListener("keydown", function(e) {
  if (e.keyCode !== 13) return;
  testarDados();
});
