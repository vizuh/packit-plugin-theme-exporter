=== PackIt Plugin & Theme Exporter ===
Contributors: hugoc, meaowsdev
Donate link: https://vizuh.com/
Tags: export, plugins, themes, backup, zip, download, migration
Requires at least: 5.0
Tested up to: 6.8
Stable tag: 1.0.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Empacote e exporte facilmente seus plugins e temas do WordPress com um único clique.

== Description ==

**PackIt Plugin & Theme Exporter** é uma ferramenta poderosa e intuitiva que permite exportar plugins e temas instalados diretamente do painel administrativo do WordPress. Perfeito para backups, migrações e compartilhamento de desenvolvimento personalizado.

**Características Principais:**

* ✅ **Interface Intuitiva** - Painel de administração limpo e fácil de usar
* ✅ **Exportação com Um Clique** - Exporte qualquer plugin ou tema instantaneamente
* ✅ **Arquivos ZIP Automáticos** - Cria automaticamente arquivos ZIP com timestamp
* ✅ **Busca em Tempo Real** - Filtre plugins e temas rapidamente
* ✅ **Visualização de Status** - Veja quais plugins/temas estão ativos
* ✅ **Seguro e Confiável** - Segue as melhores práticas de segurança do WordPress
* ✅ **100% em Português Brasileiro** - Interface totalmente traduzida

**Casos de Uso:**

* Criar backups de plugins e temas específicos
* Migrar plugins entre diferentes sites WordPress
* Compartilhar desenvolvimento personalizado com clientes
* Arquivar versões específicas para controle de versão
* Empacotar trabalho personalizado para entrega

Desenvolvido com ❤️ por [Vizuh](https://vizuh.com/).

== Installation ==

**Instalação Automática:**

1. Acesse o painel administrativo do WordPress
2. Vá para "Plugins" > "Adicionar Novo"
3. Procure por "PackIt Plugin & Theme Exporter"
4. Clique em "Instalar Agora"
5. Ative o plugin
6. Acesse "Ferramentas" > "Exportar Plugins/Temas"

**Instalação Manual:**

1. Faça o download do arquivo ZIP do plugin
2. Acesse "Plugins" > "Adicionar Novo" > "Enviar Plugin"
3. Escolha o arquivo ZIP e clique em "Instalar Agora"
4. Ative o plugin após a instalação

**Ou via FTP:**
1. Faça o upload da pasta `packit-plugin-theme-exporter` para o diretório `/wp-content/plugins/`
2. Ative o plugin através do menu "Plugins" no WordPress

== Frequently Asked Questions ==

= Como uso o PackIt? =

Após a ativação, vá para "Ferramentas" > "Exportar Plugins/Temas" no painel administrativo. Escolha a aba "Plugins" ou "Temas", encontre o item que deseja exportar e clique em "Exportar". O arquivo ZIP será baixado automaticamente.

= Onde ficam os arquivos exportados? =

Os arquivos ZIP são baixados diretamente para a pasta de downloads do seu navegador quando você clica no botão "Exportar". O plugin cria um arquivo temporário no servidor que é automaticamente excluído após o download.

= Qual o formato do arquivo exportado? =

Os arquivos são exportados como arquivos ZIP com nomenclatura automática no formato: `nome-do-item_YYYY-MM-DD_HH-MM-SS.zip`

= Posso exportar múltiplos plugins de uma vez? =

Atualmente, o PackIt exporta um plugin ou tema por vez. Para exportar múltiplos itens, você precisa clicar em "Exportar" para cada um individualmente.

= O plugin funciona com temas filhos (child themes)? =

Sim! O PackIt exporta qualquer tema instalado, incluindo temas filhos e temas customizados.

= Há limite de tamanho para exportação? =

O limite depende das configurações do seu servidor PHP (memória e tempo de execução). Plugins e temas muito grandes podem levar mais tempo para serem compactados.

= O plugin requer extensões PHP especiais? =

Sim, o PackIt requer a extensão ZipArchive do PHP para criar os arquivos ZIP. A maioria dos servidores modernos já possui essa extensão ativada. O plugin verifica isso durante a ativação.

= É seguro usar o PackIt? =

Sim! O PackIt segue as melhores práticas de segurança do WordPress, incluindo verificação de nonce, validação de permissões do usuário, sanitização de entrada e escapamento de saída.

== Screenshots ==

1. Interface principal mostrando a lista de plugins
2. Aba de temas com funcionalidade de busca
3. Confirmação antes de exportar um plugin

== Changelog ==

= 1.0.0 =
* 🎉 Lançamento inicial do PackIt Plugin & Theme Exporter
* ✨ Exportação de plugins com um clique
* ✨ Exportação de temas com um clique
* 🔍 Busca e filtragem em tempo real
* 🎨 Interface moderna e intuitiva
* 🇧🇷 100% em português brasileiro
* 🔒 Segurança com verificação de nonce e permissões
* 📦 Arquivos ZIP com timestamp automático

== Upgrade Notice ==

= 1.0.0 =
Versão inicial do PackIt Plugin & Theme Exporter. Instale agora e comece a exportar seus plugins e temas!
