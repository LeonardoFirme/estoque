# **ERP Core – Sistema de Gestão de Estoque e PDV Profissional**

[![Leonardo Firme](https://img.shields.io/badge/Leonardo_Firme-fff1f0?style=for-the-badge&logo=data:image/png;base64,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&logoColor=white)](https://github.com/LeonardoFirme)
![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=github&color=FF2D20)
![Blade](https://img.shields.io/badge/Blade-FF2D20?style=for-the-badge&logo=github&color=FF2D20)
![Github Repo Size](https://img.shields.io/github/repo-size/LeonardoFirme/estoque?style=for-the-badge&logo=github&color=000000)
![Github License](https://img.shields.io/github/license/LeonardoFirme/estoque?style=for-the-badge&logo=github&color=000000)

Sistema ERP robusto desenvolvido para controle de estoque, inteligência financeira e frente de caixa (PDV). Focado em **precisão exata** de cálculos de margem líquida, custos operacionais e logística de entradas/saídas.

## **### Diferenciais Técnicos**

* **Layout Minimalista Profissional:** Interface construída com **TailwindCSS v4 (@latest)**, utilizando padrões de design escuro/claro nativos sem o uso de colchetes arbitrários.
* **Inteligência Financeira:** Cálculo automático de margem real descontando IOF, custos operacionais e taxas de comissionamento.
* **Controle de Estoque UUID:** Arquitetura de banco de dados baseada em **UUID** para máxima segurança e rastreabilidade de produtos.
* **Performance:** Implementação de **AlpineJS** para reatividade fluida sem a necessidade de recarregamento de página em componentes críticos como dropdowns de categoria e busca de produtos.

---

## **### Funcionalidades Principais**

### **1. Catálogo de Produtos**

* Cadastro completo com suporte a imagens.
* Categorização hierárquica (Pai > Filha).
* Gestão de SKU e EAN-13 (Integrado para Scanners de código de barras).
* Exclusão individual e em massa (Bulk Destroy).

### **2. Módulo de Logística (Entradas & Saídas)**

* **Histórico de Entradas:** Reposição manual de estoque com atualização automática de custo médio.
* **Frente de Caixa (PDV):** Interface otimizada para vendas rápidas com baixa automática em lote.
* **Sangrias:** Registro de retiradas de caixa com recibos térmicos customizados.

### **3. Financeiro e Performance**

* **Relatório de Comissões:** Apuração mensal por colaborador com taxa configurável.
* **Auditoria de Descontos:** Rastreio de margens cedidas em vendas autorizadas por gerentes.
* **Fechamento de Caixa:** Resumo diário detalhado por método de pagamento (Dinheiro, Débito, Crédito).

---

## **### Requisitos de Sistema**

* **PHP:** 8.3 ou superior.
* **Laravel:** 12.x
* **Banco de Dados:** MySQL 8.0+ ou PostgreSQL.
* **Node.js:** v20+ (para compilação do Tailwind v4).

---

## **### Instalação**

1. **Clone o repositório:**
```bash
git clone https://github.com/LeonardoFirme/erp-core.git
cd erp-core

```


2. **Dependências de Backend:**
```bash
composer install

```


3. **Dependências de Frontend:**
```bash
npm install
npm run build

```


4. **Configuração de Ambiente:**
```bash
cp .env.example .env
php artisan key:generate

```


5. **Migrações e Dados Iniciais:**
```bash
php artisan migrate --seed

```


6. **Link do Storage:**
```bash
php artisan storage:link

```



---

## **### Arquitetura de Pastas (Destaques)**

* `src/proxy.ts`: Gerenciamento de redirecionamentos e middleware de segurança (NextJS 16+ Pattern).
* `app/Http/Controllers/`: Lógica exata de negócios e transações financeiras.
* `resources/views/`: Interfaces Blade com AlpineJS integrado para layout reativo.

---

## **### Contato**

**Desenvolvido por Leonardo Firme** Sênior Developer | Laravel & React Expert

[![Perfil Github](https://img.shields.io/badge/Perfil-Github-000000?style=for-the-badge&logo=github&color=000000)](https://github.com/LeonardoFirme)

---

> **Aviso:** Este projeto segue diretrizes rígidas de UI/UX, não utilize classes de bordas ou uppercase em dados sensíveis conforme os padrões de segurança estabelecidos.