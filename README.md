# ⚡ Compitator AI — Multi-Tenant Competitive Intelligence & Market Radar Platform

An enterprise-grade, multi-tenant Competitive Intelligence platform built with **Laravel**, **Livewire 3**, and **Stancl Tenancy**. Compitator AI orchestrates specialized autonomous AI agents, recursive web scrapers, tech-stack fingerprinting, and customer sentiment mining to deliver real-time competitor battlecards, pricing reverse-engineering, and 1-to-1 product gap analysis.

---

## 🌟 Key Features

### 1. 🤖 Autonomous Multi-Agent Intelligence System
- **Core Market Analysis Agent (`CompetitorAnalysisAgent`)**: Evaluates comprehensive market landscapes, strategic opportunities, and competitive moats.
- **Pricing Strategy Agent (`PricingStrategyAgent`)**: Reverse-engineers pricing packaging, enterprise tiers, add-ons, and hidden charges.
- **Sales Battlecard Agent (`SalesBattlecardAgent`)**: Generates 30-second elevator pitches, objection-handling talk tracks, and prospect trap questions for sales teams.

### 2. 🕵️ Automated Deep Intelligence Tools
- **Deep Web Scraper (`DeepWebScraperTool`)**: Crawls landing pages, traverses internal links concurrently with HTTP pools, and extracts strategic copy from pricing, feature, changelog, and about pages.
- **Technology Stack Detector (`TechStackDetectorTool`)**: Scans HTML headers, scripts, and meta tags across 5 key categories:
  - *Billing & Checkout*: Stripe, Paddle, Chargebee, Paymob, LemonSqueezy.
  - *Analytics*: Google Analytics 4, Mixpanel, Segment, PostHog, Hotjar.
  - *Customer Support*: Intercom, Crisp, Zendesk, HubSpot.
  - *Frontend / Frameworks*: Next.js, Nuxt.js, React, Vue, Tailwind CSS, WordPress.
  - *Cloud / CDN*: Cloudflare, Vercel, AWS CloudFront, Fastly.
- **Customer Sentiment Miner (`CustomerSentimentMinerTool`)**: Synthesizes ratings, review signals, customer pain points, and vulnerabilities into actionable sales exploitation strategies.

### 3. 🎯 Tenant Product Profile & 1-to-1 Gap Analysis
- Injects the tenant's own product context directly into the AI prompt window:
  - Product solution name & value proposition
  - Pricing model & target Ideal Customer Profile (ICP)
  - Key differentiators & competitive moats
- Produces direct, asymmetric comparison matrices and tactical win-themes against each analyzed competitor.

### 4. 🏢 Multi-Tenant Monolith (Database-per-Tenant)
- **Stancl Tenancy v3**: Complete database and cache isolation across enterprise partitions.
- **Automatic Lifecycle Provisioning**: Automatically creates MySQL database partitions, runs schema migrations, and seeds tenant administrators.
- **Tenant Domain Routing**: Subdomain-based identification (e.g. `acme.localhost:8000`) with isolation middleware.

### 5. 🛡️ Central Admin Command Hub
- **Tenant Data Browser**: Safely inspect partitioned tenant databases, isolated schemas, and manage tenant team users directly from central admin.
- **Subscription Plan Engine**: Multi-tier billing management localized with Egyptian Pound (EGP) and billing periods.
- **Audit & Security Trail**: Centralized immutable activity logging (`ActivityLog`) tracking admin operations and tenant user lifecycles.
- **WordPress-like Modular Block Editor**: Visual landing page and post builder supporting Hero, Feature Grid, Pricing, and FAQ blocks with bilingual translation.

### 6. 🌐 Bilingual & Luxury Aesthetics
- **Bilingual English & Arabic**: Built-in dynamic RTL/LTR layout with dedicated translations (`lang/en/tenant.php` & `lang/ar/tenant.php`).
- **Luxury Obsidian Theme**: Ambient glow horizon arcs, glassmorphism cards (`#09090b`), Plus Jakarta Sans and Cairo typography.

### 7. 🧩 Dynamic Per-Tenant Customization Layer
- Allows overriding any core service, repository, DTO, or form request on a per-tenant basis without altering shared codebase.
- High-performance caching (24h TTL) with automatic observer-driven cache invalidation and central audit trail.
- 📖 **Comprehensive Guide**: See [docs/TENANT_CUSTOMIZATION.md](docs/TENANT_CUSTOMIZATION.md).

---

## 🛠️ Technology Stack

| Layer | Technology |
| :--- | :--- |
| **Backend Framework** | Laravel 11 / 12 (PHP 8.5) |
| **Multi-Tenancy** | Stancl Tenancy v3 (Database-per-tenant partition) |
| **Reactive UI** | Livewire 3 + Alpine.js |
| **Styling & Design** | Tailwind CSS v4 + Obsidian Luxury SaaS System |
| **Localization** | `mcamara/laravel-localization` (Arabic RTL & English) |
| **Database** | MySQL (Central + Dynamic Tenant Schemas) |
| **Media & Permissions** | Spatie MediaLibrary & Spatie Laravel-Permission |
| **Code Standards** | Laravel Pint Code Formatter |

---

## 🚀 Getting Started

### Prerequisites
- PHP >= 8.2 (PHP 8.5 recommended) with `pdo_mysql`, `mbstring`, `openssl`, `curl` extensions
- Composer >= 2.x
- Node.js >= 18.x & npm
- MySQL Server

### 1. Installation
```bash
# Clone the repository
git clone git@github.com:Ahmedfargh/competitor-analyzer-.git
cd competitor-analyzer-

# Install PHP dependencies
composer install

# Install NPM dependencies & compile assets
npm install
npm run build
```

### 2. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Update your `.env` database connection:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_compitator_db
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Database Migrations & Initial Seeding
```bash
# Run central migrations and seed master admin & plans
php artisan migrate --seed
```

### 4. Running the Application
```bash
# Start Laravel development server
php artisan serve
```
The central app will be live at `http://localhost:8000` (or `http://127.0.0.1:8000`).

---

## 🔐 Default Access & Credentials

### Central Admin Hub
- **URL**: `http://localhost:8000/en/admin/login`
- **Email**: `admin@compitator.com`
- **Password**: `password`

### Provisioned Demo Tenant
- **Tenant Portal**: `http://acme.localhost:8000/login`
- **Email**: `admin@acme.com` *(or `admin@acme.localhost`)*
- **Password**: `password`

---

## 🧪 Automated Testing Suite

The application includes comprehensive feature test coverage for all multi-tenant boundaries, AI tools, and admin workflows:

```bash
# Run the entire test suite
php artisan test --compact

# Run tenant user management & subdomain auth tests
php artisan test --compact --filter=TenantUserManagementAndAuthTest

# Run competitor intelligence & deep scraper tests
php artisan test --compact --filter=CompetitorAgentDeepIntelligenceTest

# Run tenant product profile & 1-to-1 gap analysis tests
php artisan test --compact --filter=TenantProductProfileAndGapAnalysisTest

# Run tenant CRUD & layer customizer tests
php artisan test --compact --filter=AdminTenantCrudTest

# Run code style formatting check
vendor/bin/pint --format agent
```

---

## 📂 Project Architecture Overview

```text
├── Modules/
│   ├── Admin/             # Central admin command hub, plans, posts, audit logs
│   └── Tenant/            # Tenant domain controllers, auth, seeders, views
├── app/
│   ├── Ai/
│   │   ├── Agents/        # Specialist agents (CompetitorAnalysis, Pricing, Battlecard)
│   │   └── Tools/         # Intelligence tools (DeepWebScraper, TechStack, Sentiment)
│   ├── Livewire/          # Reactive Livewire 3 components (TenantManager, DataBrowser, etc.)
│   ├── Models/            # Eloquent models (Tenant, User, Post, ActivityLog, etc.)
│   ├── Observers/         # Model lifecycle observers with central audit trail
│   └── Providers/         # TenancyServiceProvider, AppServiceProvider
├── database/
│   ├── migrations/        # Central database migrations
│   └── migrations/tenant/ # Tenant database migrations (partitioned users, cache, etc.)
├── lang/
│   ├── en/                # English translations (admin, tenant, marketing)
│   └── ar/                # Arabic translations (admin, tenant, marketing)
└── routes/
    ├── web.php            # Central marketing & localized public routes
    └── tenant.php         # Subdomain-isolated tenant routes (auth:tenant)
```

---

## 📄 License
This software is open-sourced under the [MIT license](https://opensource.org/licenses/MIT).
