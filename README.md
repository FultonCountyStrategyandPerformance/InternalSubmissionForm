# InternalSubmissionForm
The internal KPI and Initiatives webform used for departmental KPI value updates

## Initial Setup

### Database Connection
Found in helpers/Connection.php, requires:
```php
$serverName="";
$user='';
$password='';
$database="";```

### PHP Server
I highly suggest [MAMP](https://www.mamp.info/en/downloads/) as a quick and easy way to set up a PHP server

### Database tables
Found in index.php under the Constants, requires:
```php
$performance_departments = "";
$performance_program_kpis = "";
$performance_program_values = "";```
