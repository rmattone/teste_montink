# 📌 Estrutura do Projeto

Este repositório contém a estrutura base de um projeto PHP MVC (Model-View-Controller). A seguir, está a organização das pastas e arquivos com uma breve explicação sobre cada um.

---

## 📂 Estrutura de Diretórios

```
/
├── app/
│   ├── config/             # Arquivos de configuração da aplicação
│   │   └── config.php      # Configuração principal (exemplo: banco de dados)
│   ├── controllers/        # Controladores da aplicação
│   ├── exceptions/         # Classes para tratamento de erros e exceções
│   ├── libraries/          # Bibliotecas personalizadas da aplicação
│   ├── models/             # Modelos que interagem com o banco de dados
│   ├── views/              # Arquivos de visualização (HTML, templates)
│
├── assets/
│   ├── css/                # Arquivos de estilos (CSS)
│   ├── images/             # Imagens utilizadas no projeto
│   ├── js/                 # Scripts JavaScript
│   ├── modules/            # Módulos JS específicos da aplicação
│
├── core/
│   ├── App.php             # Classe principal para iniciar a aplicação
│   ├── Cache.php           # Gerenciador de cache da aplicação
│   ├── Controller.php      # Classe base para os controladores
│   ├── Database.php        # Classe responsável pela conexão com o banco de dados
│   ├── Loader.php          # Autoloader de classes
│   ├── Logger.php          # Classe para logs gerais da aplicação
│   ├── Model.php           # Classe base para os modelos
│   ├── QueryLogger.php     # Classe para logs específicos de queries SQL
│   ├── Router.php          # Gerenciador de rotas
│   ├── View.php            # Gerenciador de visualizações
│
├── docs/
│   ├── cloning_project.md  # Guia para rodar o projeto localmente
│   ├── deploy.md           # Guia para realizar deploy usando bundle
│
├── index.php              # Arquivo principal que inicializa a aplicação
├── .htaccess              # Configurações do Apache para URLs amigáveis
├── .gitignore             # Arquivos e pastas ignoradas pelo Git
├── .autoload.php          # Autoload alternativo para inclusão de arquivos
├── env.php                # Carregador de variáveis definidas no .env
```

---

## 📖 Descrição das Pastas

### 📁 `app/`

Contém a lógica principal da aplicação, separada por responsabilidade:

* **config/** → Arquivos de configuração, como conexões de banco.
* **controllers/** → Controladores que tratam requisições e coordenam a lógica.
* **exceptions/** → Classes para tratamento de exceções específicas da aplicação.
* **libraries/** → Classes auxiliares, utilitárias ou de terceiros.
* **models/** → Regras de negócio e interação com banco de dados.
* **views/** → Templates HTML e páginas renderizadas para o usuário final.

### 📁 `assets/`

Arquivos estáticos utilizados pela aplicação:

* **css/** → Estilos da interface.
* **images/** → Imagens utilizadas no frontend.
* **js/** → Scripts JavaScript gerais.
* **modules/** → Scripts modulares, reutilizáveis ou com escopo específico.

### 📁 `core/`

Contém as classes fundamentais para o funcionamento do MVC:

* **App.php** → Responsável por inicializar o sistema.
* **Cache.php** → Gerencia cache de dados e arquivos.
* **Controller.php** → Classe base para todos os controladores.
* **Database.php** → Manipula conexão e operações com o banco de dados.
* **Loader.php** → Sistema de autoload para as classes.
* **Logger.php** → Gerencia logs do sistema.
* **Model.php** → Classe base para modelos.
* **QueryLogger.php** → Faz log específico das queries SQL.
* **Router.php** → Define e trata as rotas da aplicação.
* **View\.php** → Renderiza os arquivos de visualização.

### 📄 Arquivos Raiz

* **index.php** → Arquivo de entrada da aplicação, ponto inicial da execução.
* **.htaccess** → Configuração do Apache para reescrita de URLs.
* **.gitignore** → Lista de arquivos/pastas a serem ignoradas pelo Git.
* **.autoload.php** → Script alternativo para carregar classes manualmente.
* **env.php** → Script de carregamento das variáveis definidas no `.env`.



## 📆 Atualizado em: 22/07/2025
