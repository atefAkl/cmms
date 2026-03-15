# Architecture Overview

This CMMS project follows a layered architecture using the Laravel framework. The goal is to keep controllers thin and move complex business logic into dedicated services. Database operations are handled via Repositories to maintain a clear boundary of concern.

## System Modules

### 1. API Layer
Built with Laravel Sanctum, the API layer provides authentication-secured and paginated JSON responses for mobile applications and IoT sensors to communicate with the CMMS platform.
- Controllers: `App\Http\Controllers\API\`
- Resources: `App\Http\Resources\API\`
- Focus endpoints: `/api/rooms`, `/api/alerts`, `/api/maintenance-tasks`, `/api/temperature-readings`

### 2. Preventive Maintenance Engine
Driven by a daily scheduled job (`GeneratePreventiveMaintenanceTasks`), this module reads `pm_schedules` to automatically generate `MaintenanceTask` records when an equipment's maintenance drops due. It supports priority tagging and time estimation.

### 3. Work Order Lifecycle
All `MaintenanceTask` workflows run through a strict status pipeline managed by `MaintenanceTaskService`.
- **Statuses**: `open` -> `diagnosed` -> `assigned` -> `in_progress` -> `completed` -> `approved` -> `closed`
- Tracks assignment (`technician_id`), duration (`started_at`, `completed_at`), and handles business logic validations during transitions.

### 4. Inventory Management
Tracks spare parts through dual-layer validation via the `SparePartService`. It records every modification inside the `inventory_transactions` table to maintain a ledger of `in` and `out` adjustments.

### 5. Dashboards and Reporting
The UI separates `Manager` and `Maintenance` experiences using `Role` validation.
- Uses `DashboardService` with Redis `Cache::remember()` strategies (5 min expiry) to offset heavy analytical queries.
- Visuals leverage `Chart.js` for dynamic insights like Temperature Trends and Monthly Maintenance Costs.

## Design Patterns

**Service-Repository Pattern**
- **Controllers** simply validate inputs and return responses or views.
- **Services** (e.g., `SparePartService`, `MaintenanceTaskService`) execute the business logic, encapsulating algorithms like deduplication, transaction handling, or status validation.
- **Repositories** interface with Eloquent models directly to construct common queries, keeping Service classes abstracted from direct DB interaction.
