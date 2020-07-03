**RUN:**

docker-compose up

php bin/console doctrine:database:create

php bin/console doctrine:migrations:migrate

_______________________________________________
**!IMPORTANT!**

After running commands above:

Go to .env 

comment string:
DATABASE_URL=postgresql://root:root@127.0.0.1:5432/crm-system-demo?serverVersion=11.8&charset=utf8

and uncomment next:
DATABASE_URL=postgresql://root:root@postgres:5432/crm-system-demo?serverVersion=11.8&charset=utf8

Sorry for that :( I didn't find the way to resolve it properly

______________________________________________

See app:
http://localhost:8000

Adminer:
http://localhost:8080
user:root
password:root