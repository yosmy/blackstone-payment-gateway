# Test

docker network create backend

cd test

export UID
export GID
docker-compose \
-f docker/all.yml \
-p yosmy_blackstone_gateway \
up -d \
--remove-orphans --force-recreate

docker exec -it yosmy_blackstone_gateway_php sh
cd test
rm -rf var/cache/*

php bin/app.php /payment/gateway/blackstone/add-customer
php bin/app.php /payment/gateway/blackstone/add-card 5e3b8563b2399 4111111111111111 12 28 123