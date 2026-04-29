# Bus Attendance PHP + MySQL

## New updates
- Arabic-safe CSV/XLS export via UTF-8 BOM.
- Print-friendly report page for correct browser PDF printing (Arabic supported).
- Attendance now supports **Route** (`arriving`/`departure`) with default `arriving`.
- Settings menu added to edit routes and attendance statuses from dropdown source.
- Reports updated:
  - Bus report lists student-by-student statuses (not just totals).
  - Bus report supports route filter and optional specific bus filter.
  - Class report lists students in selected class and supports class filter.

## Important for existing databases
Run SQL migration or re-import `schema.sql` because `attendance` now includes `route` and `status` as flexible text plus `settings` table.
