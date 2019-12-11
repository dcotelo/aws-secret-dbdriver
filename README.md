# AWS Secret Manager db driver

### Features
- Ready to go database driver for AWS secret manager

# Install

#### Composer install

`$ composer require dcotelo/aws-secret-dbdriver`


#### Load secrets in AWS Secret Manager as  key/value pairs
Naming convention:  `<env>/<appname>/<conn_name> `
**Ex: stage/Blog/mysql**


#### Database configuration

Database configuration  `config/database.php` .

    'connections' => [

        'mysql' => [
            'driver' => 'secret-db',
        ],
    


#### Minimum secret attributes　

```javascript
{
  "database": "blog_database",
  "driver": "mysql",
  "host": "127.0.0.1",
  "password": "*******",
  "port": "3306",
  "username": "mysql_user"
}
```

#### Cache configuration
Default configuration keep the credentials in cache for 5 minutes customizable in  `.env`  file.
  
	DB_CACHE_TIME=<minutes>

#### AWS Credentials
In order to consume saved secrets aws credentials must be configured in  `.env`  
Ex:
  
	AWS_ACCESS_KEY_ID=<KEY>
	AWS_SECRET_ACCESS_KEY=<SECRET>
	AWS_REGION=<REGION>
