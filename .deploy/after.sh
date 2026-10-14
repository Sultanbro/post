# https://forums.rancher.com/t/how-to-specify-first-container-in-rancher-exec/8337/2
CONTAINER_ID=$(rancher ps -c | grep -i $1-server | cut -f 1 -d' ' | head -1)

function run() {
    echo "Running" $@
    script --return --command "rancher exec -it $CONTAINER_ID $@" /dev/null # ./output.txt

    # cat ./output.txt
    # rm -f ./output.txt
}

# Херачим миграции
curl "http://mycent.kz/api/command?command=migrate&key=123456789"

# Кешируем вьюхи
# run a view:cache
