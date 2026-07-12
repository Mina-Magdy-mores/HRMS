# 🏢 HRMS – Enterprise-Grade Human Resources Management System

[![Live Demo](https://img.shields.io/badge/Live_Demo-Laravel_Cloud-FF2D20?style=for-the-badge&logo=laravel)](https://hrms-production-5wyakl.laravel.cloud/admin/login)
[![Laravel Framework](https://img.shields.io/badge/Laravel-v13.7-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com/)
[![PHP Runtime](https://img.shields.io/badge/PHP-v8.4-777BB4?style=for-the-badge&logo=php)](https://www.php.net/)
[![Database Architecture](https://img.shields.io/badge/Database-MySQL-4479A1?style=for-the-badge&logo=mysql)](https://www.mysql.com/)
[![UI Template](https://img.shields.io/badge/UI_Template-AdminLTE_3_(RTL)-orange?style=for-the-badge)](https://adminlte.io/)

An enterprise-grade, multi-tenant SaaS Human Resources Management System (HRMS) built with **Laravel 13** and **PHP 8.4**. This production-ready system features a robust automated payroll recalculation engine, seamless biometric fingerprint device integrations, dynamic localized validation workflows, and high-performance relational database structures optimized for enterprise-level scalability.

🔗 **Production Application:** [HRMS Live Platform Portal](https://hrms-production-5wyakl.laravel.cloud/admin/login)

---

## 🚀 Key Architectural Highlights

### 1. Relational Schema & Multi-Tenant Isolation
* **Company-Scoped SaaS Architecture:** Enforces explicit data isolation across 50+ relational tables using global `company_id` constraints to power multi-tenant partitioning.
* **Complex Eloquent Interfacing:** Aggregate models (like `Employee`) handle 30+ transactional relations, integrating localized lookup dependencies for finance, structures, and geolocation matrices.
* **Hierarchical Payroll Aggregators:** Implements the `MainSalaryEmployee` database ledger containing 40+ atomic attributes, maintaining rigid composite unique keys to guarantee single monthly ledger snapshots.

### 2. High-Performance Biometric Fingerprint Engine
* **Asynchronous Fingerprint Processing:** Leverages `Maatwebsite\Excel` structures to consume physical machine attendance files, piping data directly into the custom automated execution core.
* **Idempotent Salary Variable Extraction:** Features toggleable settings to evaluate delay days, overtime boundaries, and absence totals dynamically, utilizing automated classification hooks to safely overwrite older transaction iterations without data leakage.
* **Vacation Balance State Machine:** Coordinates automated deduction matrices across remaining net thresholds while processing unmapped logs as direct monetary monthly deductions.

### 3. Service-Oriented Business Architecture (`app/Services/`)
* **BaseService Abstraction Layer:** Decouples foundational persistence workflows from outer application controllers by implementing standard object-oriented repository contracts.
* **Atomic Transaction Processing:** Wraps financial events (such as dynamic loan creations and penalty recalculations) into secure `DB::transaction()` closures to guarantee complete ACID consistency.
* **Automated Audit Trait-Driven Framework:** Attaches lifecycle triggers (`created`, `updated`, `deleting`) to transactional entities via custom traits, producing deep data mutations tracking records automatically.

### 4. Enterprise RBAC & High-Efficiency Query Optimizations
* **4-Level Permission Permissions Graph:** Powers granular action boundaries (`Role -> MainMenu -> SubMenu -> Action`) routed cleanly through authorization intercepting middlewares.
* **Static Context-Level Caching:** Deploys internal static variables inside globally shared runtime helpers to eliminate N+1 problem query footprints by caching combined system permissions map lookups.
* **Selective Subquery Filtering:** Eliminates massive looping calculations by relying heavily on highly optimized server-side native aggregates and Laravel `whereHas()` queries.

---

## 📁 Core Repository Directory Blueprint

app/
├── Helpers/                  # Global core helpers & Static permission caching
├── Http/Controllers/         # Lightweight MVC thin controllers
├── Http/Requests/            # Form validation requests with localized messaging
├── Models/                   # Rich domain models with complex Eloquent relations
├── Services/                 # Business logic processing layer (BaseService patterns)
└── Traits/                   # Cross-cutting concerns (LogsActivity, GeneralTrait)
database/
├── migrations/               # Multi-tenant optimized indexing database schemas
├── seeders/                  # Role & core environment configurations metadata
└── factories/                # High-fidelity data modeling seed structures


---


## 🛠️ Tech Stack & Core Dependencies

* **Framework Engine:** Laravel Framework v13.7 & PHP v8.4
* **Excel Data Piping:** Maatwebsite/Excel v3.1
* **Developer Tooling:** Laravel Pail (Queues), Laravel Pint (Linting), Barryvdh Laravel IDE Helper
* **User Interface Template:** AdminLTE 3 UI Template (Adapted RTL Support) & Vite Compilation

---

## ⚙️ Installation & Production Setup

Follow these layout instructions to initialize the application codebase environment locally:

1. **Clone the repository:**
   git clone https://github.com/Mina-Magdy-mores/HRMS.git
   cd HRMS

2. **Run the production-grade installation scripts wrapper:**
   composer run setup

3. **Boot local concurrently managed development server tools:**
   composer run dev

---

## 👤 Author
* **Mina Magdy Mores** – Full-Stack Engineer / Back-End Developer
* **LinkedIn:** https://www.linkedin.com/in/mina-magdy-mores
* **GitHub:** @Mina-Magdy-mores
