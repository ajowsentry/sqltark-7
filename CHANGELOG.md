# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.9.5] - 2023-03-20

### Added
- Add ODBC Driver for SQL Server connection

## [0.9.5] - 2023-03-20

### Changed
- Change `SqlTark\Connection\SqlServerConnection` implementation

## [0.9.4] - 2023-03-19

### Added
- Add `SqlTark\Connection\OracleConnection` for PDO_OCI driver
- Add `SqlTark\Connection\PostgresConnection` for PDO_PGSQL driver

### Changed
- Change `SqlTark\Connection\Config` implementation

## [0.9.3] - 2023-03-16

### Changed
- Change `TrustServerCertificate` default value to 1 in `SqlServerConnection`

## [0.9.1] - 2023-03-15

### Added
- Add `SqlTark\Manager\DbManager` class to manage multiple db configuration

### Fixed
- Fix paging implementation for SqlServer compiler

## [0.9.0] - 2023-03-13

### Added
- Added `SqlTark\Connection\*` classes
- Add implementation of `MySqlConnection`
- Add implementation of `SqlServerConnection`
- Add implementation of `SqlServerDbLibConnection`
- Create `SqlTark\XQuery` class
- Add insert, delete, and update operation

### Changed
- Change parameter type of `limit` and `offset` from `int` to `scalar`
- Change parameter of `resolveExpressionList` function in
  `SqlTark\Utilities\Helper` key type to `string|int`

## [0.1.1] - 2023-03-12

### Added
- Add support query as parameter for condition `in` clauses
- Add `compile` method in `SqlTark\Query`
- Add test cases for `SqlTark\Compiler\MySqlCompil
- Add `flatten` functin in `SqlTark\Utilities\Helper`

### Changed
- Change implementation of `clearComponents` method in `SqlTark\Query\AbstractQuery`

### Fixed
- Redirect `__toString` method in `SqlTark\Query` to `compile`
- Fix `extractAlias` function in `SqlTark\Utilities\Helper`
- Fix join type in `crossJoin` method in `SqlTark\Query\Traits\Join`
- Fix implementation of `clearComponents` method in `SqlTark\Query\AbstractQuery`

## [0.1.0] - 2023-03-09

### Added
- Component type enums
- Abstract components (Column, Condition, From, Insert, Join, Order)
- Paging components (Limit, Offset)
- Expressions classes (Column, Literal, Raw, Variable)
- Helper class to provide common logic
- Query abstraction
- Query class to store components logic
- Abstract compiler
- MySql Compiler