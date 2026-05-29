# Study_Blog

> Confidence: high
> Coverage:   100% of files under `app/code/Study/Blog`
> Updated:    2026-05-25T00:00:00Z

## 1. Purpose

`Study_Blog` is a minimal blog post module that exposes a single entity (`Post`) backed by the `study_blog_post` table. It provides a storefront listing/detail experience under the `/blog` frontName and a REST service contract for CRUD operations on posts (`app/code/Study/Blog/etc/module.xml:3`, `app/code/Study/Blog/etc/frontend/routes.xml:5-7`). The module appears to be a learning/scaffolding exercise rather than production-grade code.

## 2. Public API surface

### Service contracts (PHP)
- `Study\Blog\Api\PostRepositoryInterface` — `getById(int):PostInterface`, `save(PostInterface):PostInterface`, `deleteById(int):bool` (`app/code/Study/Blog/Api/PostRepositoryInterface.php:13-35`).
- `Study\Blog\Api\Data\PostInterface` — fields `id`, `title`, `content`, `created_at` (`app/code/Study/Blog/Api/Data/PostInterface.php:10-54`).
- DI preferences bind these to `Study\Blog\Model\Post` and `Study\Blog\Model\PostRepository` (`app/code/Study/Blog/etc/di.xml:4-5`).

### REST endpoints (all `anonymous` — see Risks)
| Method | URL | Service method |
|---|---|---|
| GET    | `/V1/blog/:id` | `getById`     |
| POST   | `/V1/blog`     | `save`        |
| PUT    | `/V1/blog`     | `save`        |
| DELETE | `/V1/blog/:id` | `deleteById`  |

Source: `app/code/Study/Blog/etc/webapi.xml:4-27`.

### Storefront routes (frontName `blog`)
- `GET /blog` → `Study\Blog\Controller\Index\Index` forwards to `post/list` (`app/code/Study/Blog/Controller/Index/Index.php:22-27`).
- `GET /blog/post/list` → `Study\Blog\Controller\Post\ListAction` renders `blog_post_list` handle (`app/code/Study/Blog/Controller/Post/ListAction.php:21-24`, `app/code/Study/Blog/view/frontend/layout/blog_post_list.xml`).
- `GET /blog/post/detail?id=<n>` → `Study\Blog\Controller\Post\Detail` dispatches `blog_study_post_detail` then renders `blog_post_detail` handle (`app/code/Study/Blog/Controller/Post/Detail.php:23-31`).

### View model
- `Study\Blog\ViewModel\Post::getList()`, `getPostCount()`, `getDetailById()` consumed by `.phtml` templates (`app/code/Study/Blog/ViewModel/Post.php:20-35`).

## 3. Configuration

- No `system.xml`, `config.xml`, or `env.php` keys are defined by this module — there is no admin configuration surface.
- No ACL/`acl.xml`, no `adminhtml` area; storefront only.

## 4. Database footprint

Single declarative-schema table `study_blog_post` (`app/code/Study/Blog/etc/db_schema.xml:4-13`):

| Column      | Type      | Notes |
|-------------|-----------|-------|
| `id`        | int unsigned, identity, PK | |
| `title`     | varchar(255), not null     | |
| `content`   | longtext, nullable         | |
| `created_at`| timestamp, default `CURRENT_TIMESTAMP`, `on_update=false` | |

Table declaration carries `onCreate="migrateDataFromAnotherTable(test_study_blog)"` — see Risks.

Whitelist tracked in `app/code/Study/Blog/etc/db_schema_whitelist.json`. Cross-reference: `.claude/output/03-database-map.md` (entry `study_blog_post`).

Data patches seed sample rows:
- `Study\Blog\Setup\Patch\Data\PopulateBlogPosts` — inserts one "An awesome post" row (`app/code/Study/Blog/Setup/Patch/Data/PopulateBlogPosts.php:40-57`).
- `Study\Blog\Setup\Patch\Data\PopulateBlogPosts1` — inserts two additional rows (`app/code/Study/Blog/Setup/Patch/Data/PopulateBlogPosts1.php:40-67`).

## 5. Integration points

### What the module plugs into
- **Frontend router** — registers frontName `blog` (`app/code/Study/Blog/etc/frontend/routes.xml:5`).
- **WebAPI router** — registers `/V1/blog*` REST routes (`app/code/Study/Blog/etc/webapi.xml`).
- **Event manager** — emits custom event `blog_study_post_detail` on every detail page view (`app/code/Study/Blog/Controller/Post/Detail.php:25-28`).
- **Schema/data setup pipeline** — declarative schema + two data patches.

### What plugs into the module
- **Self-subscribed observer** `Study\Blog\Observer\LogPostDetailView` listens to `blog_study_post_detail` and writes request params to the PSR logger (`app/code/Study/Blog/etc/frontend/events.xml:4-6`, `app/code/Study/Blog/Observer/LogPostDetailView.php:20-28`).
- No `module.xml` declares a dependency on `Study_Blog`. No plugins, virtual types, or preferences from other modules target this code (per `.claude/output/02-module-architecture.md`).
- Templates: `view/frontend/templates/post/list.phtml`, `detail.phtml`, `sidebar.phtml`, plus an unusual `view/frontend/templates/wishlist/sidebar.phtml` (see Risks).

## 6. Known risks / TODOs

### Critical
- **Anonymous REST CRUD.** All four `/V1/blog*` routes use `<resource ref="anonymous"/>` (`app/code/Study/Blog/etc/webapi.xml:7,13,19,25`). Unauthenticated callers can create, modify, and delete any post by ID. There is no rate limiting, ownership check, input validation, or CSRF mitigation. **Recommended fix:** replace `anonymous` with a real ACL resource (e.g., `Study_Blog::posts`) and gate write methods behind admin auth; if public read is required, keep only `GET` anonymous.
- **Undeclared migration source `test_study_blog`.** `db_schema.xml:4` declares `onCreate="migrateDataFromAnotherTable(test_study_blog)"`, but no table named `test_study_blog` is declared by this module or by any other module in the codebase (verified against `.claude/output/03-database-map.md`). On a fresh install the directive resolves against a non-existent source and may either silently no-op or fail `setup:upgrade` depending on Magento version. **Recommended fix:** remove the `onCreate` attribute, or declare/import the source table explicitly.

### High
- **Duplicate seed patches.** `PopulateBlogPosts` and `PopulateBlogPosts1` are near-identical data patches (`app/code/Study/Blog/Setup/Patch/Data/PopulateBlogPosts.php`, `.../PopulateBlogPosts1.php`). The `1` suffix suggests an accidental copy left in the tree; both will run on `setup:upgrade`. Neither declares `getDependencies()` ordering. **Recommended fix:** delete `PopulateBlogPosts1` or merge its rows into the first patch; if both must coexist, set explicit dependencies.
- **`echo`/`exit` inside data patches.** Both patches catch `LocalizedException` and call `echo $exception->getMessage(); exit;` (`PopulateBlogPosts.php:52-55`, `PopulateBlogPosts1.php:60-63`). This corrupts CLI output and aborts `setup:upgrade` non-recoverably. **Recommended fix:** rethrow or log via `LoggerInterface`.

### Medium
- **`PostRepository` lacks `declare(strict_types=1)`** unlike sibling files (`app/code/Study/Blog/Model/PostRepository.php:1-3`); inconsistent with the rest of the module.
- **`PostRepository::save` swallows exception class.** Re-wraps every `\Exception` as `CouldNotSaveException(__($exception->getMessage()))` (`PostRepository.php:37-41`), losing stack traces and validation context.
- **No `SearchCriteria`-based list endpoint.** Repository has no `getList()`; the view-model loads the entire collection unbounded (`ViewModel/Post.php:22`), which will not scale beyond a handful of rows.
- **No `setCreatedAt`/`getCreatedAt`-writer.** `PostInterface` exposes only `getCreatedAt` (`PostInterface.php:53`); created_at relies on DB default.
- **Stray template `view/frontend/templates/wishlist/sidebar.phtml`** under a blog module is suspicious — likely leftover from a tutorial. Verify it is not referenced by any layout XML before deleting.
- **`module.xml` declares no `setup_version`/`sequence`.** Acceptable under declarative schema, but means load order vs. core modules is undefined.

## 7. Testing

### Test run status

Last run: 2026-05-29T13:42:31Z · Result: PASS · Tests: 21 · Assertions: 34 · Failures: 0 · Errors: 0 · Skipped: 2 · Duration: 0.13s

Previous: not yet recorded — run /phpunit-run

Trend: failures —→0; cases —→21

### Test inventory

| Test class | Cases | Covers (class::methods) | Authored (UTC) |
|------------|-------|-------------------------|----------------|
| `app/code/Study/Blog/Test/Unit/Model/PostTest.php` | 4 (2 via `setAndGetProvider` + 2 standalone) | `Model\Post::setTitle,getTitle,setContent,getContent,getCreatedAt` | 2026-05-29 |
| `app/code/Study/Blog/Test/Unit/Model/PostRepositoryTest.php` | 7 (`getByIdProvider` 2 + `saveProvider` 2 + `deleteByIdProvider` 3) | `Model\PostRepository::getById,save,deleteById` | 2026-05-29 |
| `app/code/Study/Blog/Test/Unit/Observer/LogPostDetailViewTest.php` | 1 | `Observer\LogPostDetailView::execute` | 2026-05-29 |
| `app/code/Study/Blog/Test/Unit/ViewModel/PostTest.php` | 4 (`getDetailByIdProvider` 2 + 2 standalone) | `ViewModel\Post::getList,getPostCount,getDetailById` | 2026-05-29 |
| `app/code/Study/Blog/Test/Unit/Controller/Index/IndexTest.php` | 1 | `Controller\Index\Index::execute` | 2026-05-29 |
| `app/code/Study/Blog/Test/Unit/Controller/Post/ListActionTest.php` | 1 | `Controller\Post\ListAction::execute` | 2026-05-29 |
| `app/code/Study/Blog/Test/Unit/Controller/Post/DetailTest.php` | 1 | `Controller\Post\Detail::execute` | 2026-05-29 |
| `app/code/Study/Blog/Test/Unit/Model/ResourceModel/PostTest.php` | 1 (skipped) | `Model\ResourceModel\Post::_construct` (thin `_init` wiring — integration only) | 2026-05-29 |
| `app/code/Study/Blog/Test/Unit/Model/ResourceModel/Post/CollectionTest.php` | 1 (skipped) | `Model\ResourceModel\Post\Collection::_construct` (thin `_init` wiring — integration only) | 2026-05-29 |

## Open Questions

- Is `test_study_blog` an artifact of a developer's local DB, or is it expected to be provisioned by a fixture not in this repo? Unable to determine in available time; recommended follow-up: grep deployment scripts and DB dumps for `test_study_blog`.
- Was the `anonymous` ACL on POST/PUT/DELETE intentional (public sandbox) or an oversight? Recommended follow-up: confirm with the original author or the `Study_*` learning track owner.
- Is `PopulateBlogPosts1` intended to be a versioned successor to `PopulateBlogPosts`, or a duplicate to delete? Recommended follow-up: check VCS history of `Setup/Patch/Data/` once a git repo is available (this workspace is not a git repo).
- Does the `wishlist/sidebar.phtml` template indicate an abandoned wishlist integration?
