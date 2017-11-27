#!/bin/bash

if [ ! -n "$1" ] ;then  
    echo "请输入参数：class|conf|tools|all"
else  
    if [ $1 == "all" ]; then
        scp -r  -i ~/.ssh/liaogou-us.pem lotto-hk/ ec2-user@13.58.80.72:/data/app/lotto-hk/
    else
        scp -r  -i ~/.ssh/liaogou-us.pem lotto-hk/$1 ec2-user@13.58.80.72:/data/app/lotto-hk/
    fi
fi  



