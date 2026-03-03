resolution=$1
main_sub_part=$2
committee=$3
conference=$4
echo "resolution=$1;main_sub_part=$2;committee=$3;conference=$4; -> qrcodes/RESOMUN_$1.png"
qr "resolution=$1;main_sub_part=$2;committee=$3;conference=$4;" > qrcodes/RESOMUN_$1.png