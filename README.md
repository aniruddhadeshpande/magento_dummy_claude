# Adobe Commerce Storefront

This repository hosts an **Adobe Commerce (Enterprise Edition) 2.4.7-p3** application,
extended with a set of custom modules under the `Accenture` and `Study` vendor
namespaces. It follows the standard Magento 2 project layout: the platform and most
modules are delivered through Composer, while project-specific code lives in
`app/code/`.

---

## Platform / Version

| Property        | Value                                     |
|-----------------|-------------------------------------------|
| Edition         | Adobe Commerce (Enterprise Edition)       |
| **Current version** | **2.4.7-p3** (2.4.7, Patch 3)         |
| PHP             | 8.1 – 8.3 (`~8.1.0 \|\| ~8.2.0 \|\| ~8.3.0`) |
| Composer name   | `magento/project-enterprise-edition`      |
| License         | Proprietary                               |

> The active release is **Magento / Adobe Commerce 2.4.7-p3**, pinned in
> `composer.json` via `"magento/product-enterprise-edition": "2.4.7-p3"`.

---

## Technology Stack

The 2.4.7-p3 platform runs on the standard Adobe Commerce service stack:

| Layer            | Technology                          | Notes                                  |
|------------------|-------------------------------------|----------------------------------------|
| Search           | Elasticsearch 7.17 / 8.x · OpenSearch 1.x / 2.x | Catalog search & indexing engine |
| Message queue    | RabbitMQ (`php-amqplib` ^3.2)       | Asynchronous / deferred processing     |
| Cache & session  | Redis (`cache-backend-redis`, `credis`, `php-redis-session-abstract`) | Page/data cache and session store |
| Database         | MySQL / MariaDB (`ext-pdo_mysql`)   | Primary data store                     |
| Web server       | Nginx or Apache + PHP-FPM           | See `nginx.conf.sample` / `.htaccess`  |

### Additional Composer packages

Beyond the core platform, the root `composer.json` requires:

- `markshust/magento2-module-disabletwofactorauth` — two-factor-auth toggle helper.
- `markshust/magento2-module-simpledata` — sample/test data helper.
- `miniorange_inc/idpsaml` — SAML identity-provider / SSO integration.

---

## Repository Layout

```
.
├── app/
│   ├── code/            # Custom modules (Accenture, Study, …) — tracked
│   └── etc/             # env.php / config.php are git-ignored (environment-specific)
├── bin/                 # bin/magento CLI entry point
├── lib/                 # Magento framework libraries
├── pub/                 # Web document root (index.php, static/, media/)
├── setup/               # Installer
├── dev/                 # Developer tooling and tests
├── vendor/              # Composer dependencies            (git-ignored)
├── generated/           # Compiled DI / interceptors       (git-ignored)
└── var/                 # Cache, logs, sessions, page cache (git-ignored)
```

`vendor/`, `generated/`, `var/`, `pub/static/`, and `pub/media/` are regenerable and
excluded from version control (see `.gitignore`).

---

## Custom Modules

Project-specific modules live in `app/code/`, grouped by vendor namespace.

### Accenture

| Module | Purpose | Dependencies |
|--------|---------|--------------|
| `Accenture_SkipCatalogProductPriceIndexing` | Redefines the catalog product price indexer behavior. | `Magento_Catalog` |

### Study

| Module | Purpose | Dependencies |
|--------|---------|--------------|
| `Study_Blog` | Blog post entity with CRUD service contracts plus REST (`/V1/blog`) and storefront (`/blog`) routes. | — |
| `Study_BlogExtra` | Extends / enhances `Study_Blog`. | `Study_Blog` |
| `Study_CheckoutMessages` | Adds informational messages (money-back guarantee, shipping time) to the One Page Checkout sidebar. | `Magento_Checkout` |
| `Study_CustomCheckout` | Custom checkout customizations. | — |
| `Study_FreeShippingPromo` | Free-shipping promotion functionality. | — |
| `Study_GraphQl` | GraphQL schema / resolver enhancements. | — |
| `Study_InventoryFulfillment` | Inventory fulfillment operations. | — |
| `Study_JsStudy` | JavaScript integration study module. | — |
| `Study_Ko` | Knockout.js component study module. | — |
| `Study_ProductCompare` | Product comparison functionality. | `Magento_Catalog` |
| `Study_Sentimate` | Sentiment analysis for product reviews; publishes review data to a message queue (`study.sentimate.reviews`) for asynchronous processing. | — |

> **Third-party, code-resident packages:** `MSP_DevTools` (MageSpecialist developer
> tools) and the miniorange SAML integration also reside under `app/code` / `vendor`.
> They are external components and are not part of the custom project codebase.

---

## Getting Started

Local setup of the Adobe Commerce application:

```bash
# 1. Install dependencies (requires authenticated repo.magento.com credentials)
composer install

# 2. Install the application (first-time setup)
bin/magento setup:install \
  --base-url=http://localhost/ \
  --db-host=localhost --db-name=magento --db-user=magento --db-password=*** \
  --search-engine=opensearch --opensearch-host=localhost --opensearch-port=9200 \
  --admin-firstname=Admin --admin-lastname=User \
  --admin-email=admin@example.com --admin-user=admin --admin-password=***

# 3. Or, on an existing database, apply schema/data updates
bin/magento setup:upgrade

# 4. Compile dependency injection and deploy static assets
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f

# 5. Flush caches
bin/magento cache:flush
```

For asynchronous features (e.g. `Study_Sentimate`), run the queue consumers and cron:

```bash
bin/magento cron:install         # register the cron schedule
bin/magento queue:consumers:list # discover available consumers
bin/magento queue:consumers:start <consumer-name>
```

---

## Common Commands

| Task | Command |
|------|---------|
| Flush cache | `bin/magento cache:flush` |
| Enable / disable cache types | `bin/magento cache:enable` / `cache:disable` |
| Reindex all | `bin/magento indexer:reindex` |
| Set indexer mode | `bin/magento indexer:set-mode {realtime\|schedule}` |
| Apply schema/data changes | `bin/magento setup:upgrade` |
| Compile DI | `bin/magento setup:di:compile` |
| Deploy static content | `bin/magento setup:static-content:deploy -f` |
| Run cron | `bin/magento cron:run` |
| Start a queue consumer | `bin/magento queue:consumers:start <name>` |
| List enabled modules | `bin/magento module:status` |
| Set deploy mode | `bin/magento deploy:mode:set {developer\|production}` |

---

## Documentation

Additional architecture and onboarding notes are maintained in the repository root as
plain Markdown files: `ARCHITECTURE.md`, `ONBOARDING.md`, and `RUNBOOK.md`.
Per-module documentation lives alongside each module as
`app/code/<Vendor>/<Module>/README.md`.
