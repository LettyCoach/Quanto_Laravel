function getNumber(_str){
  if(_str==null) return '0';
  var arr = _str.split('');
  var out = new Array();
  for(var cnt=0;cnt<arr.length;cnt++){
    if(isNaN(arr[cnt])==false){
      out.push(arr[cnt]);
    }
  }
  return Number(out.join(''));
}

var num = getNumber(dis_total);
if(num==0){
  dis_total='';
}else{
  dis_total=num.toLocaleString();
}
 dis_data=dis_data.replaceAll('&quot;','\"');
 dis_data=dis_data.replaceAll(':\"{',':{');
 dis_data=dis_data.replaceAll('}\",','},');
 dis_data=dis_data.replaceAll('\n','');
 dis_data=dis_data.replaceAll('　','');
 dis_data=dis_data.replaceAll('	','');
 dis_data=dis_data.replaceAll(' ','');
 dis_data=dis_data.replaceAll('\n','');
 dis_data=dis_data.replaceAll('\r','').replaceAll(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/<[^>]*>/g, '');

var answers=JSON.parse(dis_data);