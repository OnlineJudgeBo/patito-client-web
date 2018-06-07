for((i=0;i<254;i++))
do
 curl -X POST http://coj.uci.cu/user/getInstitution.xhtml --data country=$i > $i.json
done
