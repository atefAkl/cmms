# Database Schema & Relationships

This project extends an existing Laravel application for Cold Storage CMMS. The design heavily utilizes Eloquent relationship standards, polymorph relationships, and indexing.

## Core Models
- **Room**: `hasMany` Compressors, `hasOne` Evaporator, `hasMany` TemperatureReadings.
- **Compressor**: `belongsTo` Room.
- **Evaporator**: `belongsTo` Room.
- **User**: Represents technicians, managers, etc. `hasMany` MaintenanceTasks.

## Maintenance & Work Orders
- **MaintenanceTask**
  - **Relationships**: `belongsTo` Room, `belongsTo` Compressor, `belongsTo` User (technician), `hasMany` MaintenanceParts.
  - **Fields**: Tracks `started_at`, `completed_at`, `cost`, `status`.

- **PmSchedule**
  - **Relationships**: `morphTo` equipment (Room, Compressor, Evaporator).
  - **Purpose**: Defines recurrence for preventive maintenance. When `next_due` is <= today, a `MaintenanceTask` is created.

## Inventory Ledger
- **SparePart**
  - Uses `InventoryTransaction` to deduce or increment its `stock`.
  
- **InventoryTransaction**
  - **Fields**: `type` (`in`/`out`), `quantity`, `reference_type` (polymorphic structure), `reference_id`.
  - Serves as the immutable ledger for part usages.

- **Supplier**
  - **Fields**: `name`, `contact_email`, `contact_phone`.

## IoT & Sensors
- **TemperatureReading**
  - Logs timeseries data for temperatures per `Room`.
- **Alert**
  - Generated either manually or through threshold breaches. Holds `is_resolved` status.
