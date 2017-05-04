# InternalSubmissionForm
The internal KPI and Initiatives webform used for departmental KPI value updates

## Initial Setup

### Database Connection
Found in [helpers/Connection.php](https://github.com/FultonCountyStrategyandPerformance/InternalSubmissionForm/blob/master/helpers/Connection.php), requires:
```php
$serverName="";
$user='';
$password='';
$database="";
```

### PHP Server
I highly suggest [MAMP](https://www.mamp.info/en/downloads/) as a quick and easy way to set up a PHP server.

### Database tables
Database table setup can be found in the [SQLScripts Repository](https://github.com/FultonCountyStrategyandPerformance/SQLScripts)

Found in index.php under the Constants, requires:
```php
$performance_departments = "PerformanceManagement_Departments";
$performance_program_kpis = "PerformanceManagement_ProgramKPIs";
$performance_program_values = "PerformanceManagement_ProgramValues";
$performance_program_values_staging="PerformanceManagement_ProgramValues_staging"
```
## User Workflow Diagram

![workflow](/images/InputFormWorkflow.png)
