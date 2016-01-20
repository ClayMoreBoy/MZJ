#!/bin/bash

#scp -r qm-league/target/qm-league-1.1 support@182.254.171.210:/data/web/qiumi-1.0

if [ ! -n "$1" ] ;then  
    echo "请输入参数：class|conf|tools|all"
else  
    if [ $1 == "class" ]; then
        scp -r target/jmaox-1.0/WEB-INF/classes/ support@182.254.171.210:/data/web/jmaox-1.0/WEB-INF/
    elif [ $1 == "ftl" ]; then
        scp -r target/jmaox-1.0/ftl support@182.254.171.210:/data/web/jmaox-1.0/
    elif [ $1 == "static" ]; then
        scp -r target/jmaox-1.0/static support@182.254.171.210:/data/web/jmaox-1.0/
    elif [ $1 == "all" ]; then
        scp -r target/jmaox-1.0/ support@182.254.171.210:/data/web/
    else
        echo "请输入参数：class|conf|tools|all"
    fi
fi  



